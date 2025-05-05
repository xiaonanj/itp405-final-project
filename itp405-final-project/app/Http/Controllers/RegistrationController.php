<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Hash;
use Auth;

class RegistrationController extends Controller
{
    public function index()
    {
        return view('registration.index');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        Auth::login($user);

        return redirect()->route('rounds.index');
    }
}

