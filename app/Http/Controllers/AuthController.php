<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;


class AuthController extends Controller
{
    public function login(Request $request){
        try{


        $validated = $request->validate([
            'email'=>'required',
            'password'=>'required|min:5|max:8',
        ]);

        $credentials = [
            'email' => $validated['email'],
            'password' => $validated['password']
        ];

        $user = User::where('email',$validated['email'])->first();
        $token = JWTAuth::attempt($credentials);


        if($token){
             return response()->json([
                'success' => true,
                'user' => $user,
                'token' => $token,
                'message' => 'succcessfull login'
            ]);

        }else{

             return response()->json([
                'success' => false,
                'message' => 'Failed to login'
            ]);
        }
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }

    public function register(Request $request){
        $validated = $request->validate([
            'name'=>'required|min:3|max:10',
            'email'=>'required|unique:users,email',
            'password'=>'required|min:5|max:8',
            'phone' => 'required|digits:10'
        ]);

        if($validated){
            $data = [
                'name' => $validated['name'],
                'email'=> $validated['email'],
                'password'=> Hash::make($validated['password']),
                'phone'=> $validated['phone'],
                'role' => 2,
            ];

            $user = User::create($data);
            $token =JWTAuth::fromUser($user);
            return response()->json([
                'success' => true,
                'user' => $user,
                'token' => $token,
                'message' => 'succcessfull registered'
            ]);

        }else{
            return response()->json([
                'success' => false,
                'message' => 'Error found'
            ]);
        }

    }
}
