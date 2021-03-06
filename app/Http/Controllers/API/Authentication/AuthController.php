<?php

namespace App\Http\Controllers\API\Authentication;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmail;
use App\Mail\SuccessfulRegistration;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\ThrottlesLogins;

class AuthController extends Controller
{
    use ThrottlesLogins;

    protected $maxAttempts = 5;
    protected $decayMinutes = 5;
    public function username()
    {
        return 'email';
    }

    protected function sendLockoutResponse(Request $request)
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );
    }

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
            $err = $e->errors()['email'];
            if(in_array('The email has already been taken.', $err, TRUE))
            {
                return response()->json(['message' => 'Email already taken'], 400);
            }
            else
            {
                return response()->json(['message' => $err[0]]);
            }
        }

        $this->dispatch(new SendEmail($validated['email'], new SuccessfulRegistration()));
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
            if (method_exists($this, 'hasTooManyLoginAttempts') &&
                $this->hasTooManyLoginAttempts($request)) {
                $this->fireLockoutEvent($request);
                return response()->json(['message' => 'Your Account is locked for ' . $this->decayMinutes . ' minutes'], 401);
            }
            $this->incrementLoginAttempts($request);
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $this->clearLoginAttempts($request);
        $token = auth()->user()->createToken('authToken')->accessToken;
        return response()->json(['access_token' => $token], 201);
    }
}
