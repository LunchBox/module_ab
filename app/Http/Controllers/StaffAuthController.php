<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class StaffAuthController extends Controller
{
    public function signin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:4',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'data can not be processed'], 422);
        }

        $staff = Staff::where('email', $request->email)->first();

        if (!$staff || !Hash::check($request->password, $staff->password)) {
            return response()->json(['message' => 'staff credentials are invalid'], 401);
        }

        $token = Str::uuid();
        Token::create([
            'token' => $token,
            'tokenable_id' => $staff->id,
            'tokenable_type' => Staff::class,
        ]);

        return response()->json([
            'message' => 'success',
            'data' => [
                'id' => $staff->id,
                'email' => $staff->email,
                'firstname' => $staff->firstname,
                'lastname' => $staff->lastname,
                'token' => $token,
                'role' => $staff->role,
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