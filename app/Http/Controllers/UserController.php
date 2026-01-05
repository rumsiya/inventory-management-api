<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('getRole')->get();
        return response()->json([
            'success'=> true,
            'user'=> $users
        ]) ;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'=>'required|min:3|max:10',
            'email'=>'required',
            'password'=>'required|min:5|max:8',
            'phone' => 'required|digits:10',
            'role'=>'required'
        ]);

        if($validated){
            $data = [
                'name' => $validated['name'],
                'email'=> $validated['email'],
                'password'=> Hash::make($validated['password']),
                'phone'=> $validated['phone'],
                'role' => $validated['role']??2,
            ];

            $user = User::create($data);
            return response()->json([
                'success' => true,
                'user' => $user->load('getRole'),
                'message' => 'succcessfull created'
            ]);

        }else{
            return response()->json([
                'success' => false,
                'message' => 'Error found'
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::with('getRole')->find($id);
        return response()->json([
            'success' => true,
            'user' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id);
        $validated = $request->validate([
            'name'=>'required|min:3|max:10',
            'email'=>'required',
            'phone' => 'required|digits:10',
            'role'=>'required'
        ]);

        if($validated){
            $data = [
                'name' => $validated['name'],
                'email'=> $validated['email'],
                'phone'=> $validated['phone'],
                'role' => $validated['role']??2,
            ];

            if($user->update($data)){
                return response()->json([
                'success' => true,
                'user' => User::with('getRole')->find($id),
                'message' => 'succcessfully updated'
                ]);
            }

        }
        else{
            return response()->json([
                'success' => false,
                'message' => 'Error found'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user=  User::find($id);
        if($user->delete()){
            return response()->json([
                'success' => true,
                'message' => 'successfully deleted'
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'error found'
            ]);
        }
    }
}
