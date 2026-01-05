<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $units = Unit::all();
        return response()->json([
            'success' => true,
            'units' => $units
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'unit_name' =>'required|min:2|max:8'
        ]);
        if($validated){
            $data = [
                'unit_name' => $validated['unit_name']
            ];

            $unit = Unit::create($data);
            if($unit){
                return response()->json([
                    'success' => true,
                    'units' => $unit,
                    'message' => 'successfully created',

                ]);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Failed',

                ]);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $unit = Unit::find($id);
        return response()->json([
            'success' => true,
            'unit' => $unit,

        ]) ;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $unit = Unit::find($id);
        $validated = $request->validate([
            'unit_name' =>'required|min:2|max:8'
        ]);
        if($validated){
            $data = [
                'unit_name' => $validated['unit_name']
            ];


            if($unit->update($data)){
                return response()->json([
                    'success' => true,
                    'units' => Unit::find($id),
                    'message' => 'successfully updated',

                ]);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Failed',

                ]);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $unit = Unit::find($id);
        if($unit->delete()){
            return response()->json([
                'success' => true,
                'message' => 'successfully deleted'
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Error'
            ]);
        }
    }
}
