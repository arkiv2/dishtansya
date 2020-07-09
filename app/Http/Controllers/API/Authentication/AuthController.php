<?php

namespace App\Http\Controllers\API\Authentication;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'email' => 'email|required|unique:users',
            'password' => 'required',
        ]);

        $validated['password'] = bcrypt($request->input('password'));

        User::create($validated);

        return response()->json(['message' => 'User successfully registered'], 201);
    }
}
