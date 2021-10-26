<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    //
    public function signup(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed'
        ]);
        // create user
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);

        $user->save();

        //create Token

        $token = $user->createToken('mytoken')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }
    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        return response()->json(['message'=>'user logged out']);
    }
    public function login(Request $request)
    {
        // Check Email
        $user = User::where('email','=',$request->email)->first();
        // check password
        if(!$user || !Hash::check($request->password, $user->password)){
            return response()->json(['message'=>'Bad Login'],401);
        }
        //create Token

        $token = $user->createToken('mytoken')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }
    // "token": "1|coMJtlTPDTdvQr7a9Db3wo0QcwPJ9HfKAzqSU8qq"
}
