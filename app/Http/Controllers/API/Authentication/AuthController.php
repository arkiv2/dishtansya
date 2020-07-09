<?php

namespace App\Http\Controllers\API\Authentication;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try
        {
            $validated = $request->validate([
                'email' => 'email|required|unique:users',
                'password' => 'required',
            ]);
            $validated['password'] = bcrypt($request->input('password'));
            User::create($validated);
        }
        catch(ValidationException $e)
        {
            if($e->getMessage() == 'The given data was invalid.')
            {
                return response()->json(['message' => 'Email already taken'], 400);
            }
        }

        return response()->json(['message' => 'User successfully registered'], 201);
    }

    public function login(Request $request)
    {
        $authData = $request->validate([
            'email' => 'email|required',
            'password' => 'required',
        ]);

        if(!auth()->attempt($authData))
        {
            return response()->json(['message' => 'Invalid Credentials']);
        }

        $token = auth()->user()->createToken('authToken')->accessToken;
        return response()->json(['access_token' => $token], 201);
    }
}
