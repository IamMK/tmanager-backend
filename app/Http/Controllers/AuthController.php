<?php

namespace App\Http\Controllers;

require 'C:\Users\MK Webdev\vendor\autoload.php';

use Dotenv\Validator;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $http = new Client();
        try {
            $response = $http->request('POST', config('services.passport.login_endpoint'),
                [
                    'form_params' => [
                        'grant_type' => 'password',
                        'client_id' => config('services.passport.client_id'),
                        'client_secret' => config('services.passport.client_secret'),
                        'username' => $request->username,
                        'password' => $request->password,
                    ]
                ]
                    );
                    return $response->getBody();
        }
        catch (ClientException $e){
            if($e->getCode() === 400) {
                return response()->json('Invalid Request. Please enter a username or a password.', $e->getCode());
            }
            else if($e->getCode() === 401){
                return response()->json('Your credentials are incorrect. Please try again', $e->getCode());
            }

            return response()->json('Something went wrong on the server.', $e->getCode());
        }
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6'
        ]);
        // return response()->json($request, 200);

        return User::create([
            'name' => $request->name,
            'team_id' => $request->team_id,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => $request->is_admin
        ]);
    }

    public function logout()
    {
        auth()->user()->tokens->each(function ($token, $key){
            $token->revoke();
        });
        return response()->json('Logged out succesfully', 200);
    }

}
