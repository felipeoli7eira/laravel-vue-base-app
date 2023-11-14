<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function form()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'min:3', 'max:255'],
            'email'    => ['required', 'string', 'email', 'min:5', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'max:255', 'confirmed'],
        ]);

        $data = $request->only(['name', 'email', 'password']);
        // $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        return redirect()->route('home');
    }
}
