<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UserProfileUpdate;
use App\Models\User as ModelsUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\User\UserStore;
use App\Http\Requests\User\UserUpdate;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class User extends BaseController
{
    public function getCurrentAuthUser()
    {
        if (!auth()->check()) {
            return $this->sendError('Unauthenticated', code: 401);
        }

        $user = ModelsUser::where('id', auth()->user()->id)->with('roles')->first();
        $user->role = $user->roles->first()->name;
        unset($user->roles);

        return $this->sendResponse('Usuário autenticado', response_data: $user);
    }

    /**
     * Get user roles
     *
     * @return \Illuminate\Support\Collection
     */
    public function roles()
    {
        return DB::table('roles')->get();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = ModelsUser::where('id', '!=', auth()->user()->id)->with('roles')->with('permissions')->get()->map(function($user) {
            $user->role = $user->roles->first()->name;
            unset($user->roles);
            return $user;
        });
        return $this->sendResponse(response_data: $users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user_roles = $this->roles();
        return view('pages.dashboard.users.create', compact('user_roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserStore $request)
    {
        $payload = $request->safe()->only(['name', 'email', 'password']);
        $payload['password'] = Hash::make($payload['password']);

        $permissions = Role::findByName($request->role, 'web')
        ->permissions()
        ->get()
        ->pluck('name')
        ->toArray();

        ModelsUser::create($payload)->assignRole($request->role)->givePermissionTo($permissions);

        return $this->sendResponse('Novo usuário cadastrado', http_code: 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $user_roles = $this->roles();

        $user = ModelsUser::findOrFail($id);

        return view('pages.dashboard.users.update', compact('user_roles', 'user'));
    }

    /**
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function findById(int $id)
    {
        $user = ModelsUser::where('id', $id)->with('roles')->first();
        $user->role = $user->roles[0]->name;
        unset($user->roles);

        return $this->sendResponse(response_data: $user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdate $request, int $id)
    {
        $payload = $request->safe()->only(['name', 'email', 'password']);

        if (isset($payload['password'])) {
            $payload['password'] = Hash::make($payload['password']);
        }

        $payload = array_filter($payload, fn($input) => !is_null($input));

        if (sizeof($payload) === 0) {
            return $this->sendResponse('Nada para atualizar');
        }

        $user = ModelsUser::where('id', $id)->first();

        if (!$user->exists()) {
            return $this->sendResponse('Usuário não encontrado', http_code: 404);
        }

        if (!is_null($request->input('role')) && $request->input('role') !== $user->roles()->get()->pluck('name')->first()) {
            $permissions = Role::findByName($request->input('role'), 'web')->permissions()->get()->pluck('name')->toArray();

            $user->syncRoles([$request->input('role')]);
            $user->syncPermissions($permissions);
        }

        $update = ModelsUser::where('id', $id)->update($payload);

        if ($update) {
            return $this->sendResponse('Usuário atualizado');
        }

        return $this->sendError('Erro ao atualizar o usuário');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $user_id)
    {
        $user = ModelsUser::where('id', $user_id)->first();

        if (!$user->exists()) {
            return $this->sendError('Usuário não encontrado', code: 404);
        }

        $role = $user->roles()->pluck('name')->first();
        $permissions = Role::findByName($role, 'web')->permissions()->get()->pluck('name')->toArray();

        foreach ($permissions as $permission) {
            $user->revokePermissionTo($permission);
        }

        $delete = $user->delete();

        if (!$delete) {
            return $this->sendError('Erro ao tentar deletar o usuário');
        }

        return $this->sendResponse('Usuário deletado');
    }

    /**
     * Displays the authenticated user's profile data
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function showAuthUserProfile()
    {
        $user = ModelsUser::where('id', '=', auth()->user()->id)->first();

        return view('pages.dashboard.users.profile', compact('user'));
    }

    public function updateAuthUserProfile(UserProfileUpdate $request)
    {
        $payload = $request->safe()->only(['name', 'email', 'password']);

        $inputs = array_filter($payload, fn($input) => !is_null($input));

        if (sizeof($inputs)) {
            if (array_key_exists('password', $inputs)) {
                $inputs['password'] = Hash::make($inputs['password']);
            }

            $update = ModelsUser::where('id', '=', auth()->user()->id)->update($inputs);
            return redirect()->back()->with('updated', true);
        }

        // nothing to update
        return redirect()->back();
    }
}
