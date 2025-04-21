<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:clients',
            'password' => 'required|min:4',
            'repeat_password' => 'required|same:password',
            'phone_number' => 'required|min:8|max:12',
            'firstname' => 'required',
            'lastname' => 'required',
        ]);

        if ($validator->fails()) {
            if ($validator->errors()->has('repeat_password')) {
                return response()->json(['message' => 'repeat_password is not same with password field'], 422);
            }
            return response()->json(['message' => 'data can not be processed'], 422);
        }

        $client = Client::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_number' => $request->phone_number,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
        ]);

        $token = Str::uuid();
        Token::create([
            'token' => $token,
            'tokenable_id' => $client->id,
            'tokenable_type' => Client::class,
        ]);

        return response()->json([
            'message' => 'success',
            'data' => [
                'id' => $client->id,
                'email' => $client->email,
                'phone_number' => $client->phone_number,
                'firstname' => $client->firstname,
                'lastname' => $client->lastname,
                'token' => $token,
            ]
        ], 201);
    }

    public function signin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:4',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'data can not be processed'], 422);
        }

        $client = Client::where('email', $request->email)->first();

        if (!$client || !Hash::check($request->password, $client->password)) {
            return response()->json(['message' => 'client credentials are invalid'], 401);
        }

        $token = Str::uuid();
        Token::create([
            'token' => $token,
            'tokenable_id' => $client->id,
            'tokenable_type' => Client::class,
        ]);

        return response()->json([
            'message' => 'success',
            'data' => [
                'id' => $client->id,
                'email' => $client->email,
                'firstname' => $client->firstname,
                'lastname' => $client->lastname,
                'token' => $token,
            ]
        ]);
    }

    public function signout(Request $request)
    {
        $token = $request->query('token');
        Token::where('token', $token)->delete();
        
        return response()->json(['message' => 'success']);
    }
}