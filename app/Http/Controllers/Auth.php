<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;

class Auth extends Controller
{
    function register(Request $req)
    {
        $data = $req->validate([
            'name' => 'required|string|min:2',
            'email' => 'required|email',
            'password' => 'required|min:8|max:16'
        ]);

        $checkUser = User::where(['email' => $data['email']])->exists();

        if ($checkUser)
            throw new Exception('User with this email already exists.');

        $user =  User::create($data);
        return $user;
    }

    function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8|max:16'
        ]);

        $user = User::where(['email' => $data['email']])->first();

        if (!$user)
            return response()->json(['error' => 'user not found'], 404);

        if (!Hash::check($data['password'], $user['password']))
            return response()->json(['error' => 'email or passwor is wrong'], 401);

        $token = $user->createToken('auth-token')->plainTextToken;
        return response()->json(['access_token' => $token]);
    }
}
