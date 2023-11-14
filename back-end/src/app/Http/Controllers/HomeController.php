<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        // $user = $request->user();
        // $user = Auth::user();
        $user = auth()->user();

        return view('home', ['user' => $user]);
    }
}
