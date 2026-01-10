<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();
        return response()->json([
            'success' => true,
            'roles' => $roles
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'role_name' => 'required|min:3|max:20|unique:roles,role_name'
        ]);

        if($validated){
            $role = Role::create([
                'role_name' => $validated['role_name']
            ]);

            return response()->json([
                'success' => true,
                'role' => $role,
                'message' => 'Role created successfully'
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Validation failed'
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $role = Role::find($id);
        if($role){
            return response()->json([
                'success' => true,
                'role' => $role
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Role not found'
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $role = Role::find($id);
        $validated = $request->validate([
            'role_name' => 'required|min:3|max:20|unique:roles,role_name,'.$id
        ]);

        if($validated){
            $role->role_name = $validated['role_name'];
            $role->save();

            return response()->json([
                'success' => true,
                'role' => $role,
                'message' => 'Role updated successfully'
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Validation failed'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::find($id);
        if($role){
            $role->delete();
            return response()->json([
                'success' => true,
                'message' => 'Role deleted successfully'
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Role not found'
            ]);
        }
    }
}
