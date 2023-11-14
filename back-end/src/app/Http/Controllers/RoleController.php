<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RoleController extends BaseController
{
    public function getRoleNames()
    {
        $roles = Role::get()->pluck('name');

        // TODO: achar uma forma melhor de fazer isso
        if (auth()->user()->roles->first()->name !== 'super_admin') {
            unset($roles['super_admin']);
        }

        return $this->sendResponse(response_data: $roles);
    }
}
