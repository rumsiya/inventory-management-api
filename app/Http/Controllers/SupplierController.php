<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $supplier = Supplier::all();
        return response()->json([
            'success' => true,
            'supplier' => $supplier
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_name' => 'required|min:3|max:10',
            'phone' => 'required'
        ]);

        if($validated){
            $data = [
                'supplier_name' => $validated['supplier_name'],
                'phone' => $validated['phone']
            ];

            $supp = Supplier::create($data) ;
            if($supp){
                return response()->json([
                    'success' => true,
                    'supplier' => $supp,
                    'message' => 'successfully created'
                ]) ;
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Error'
                ]) ;
            }
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $supp = Supplier::find($id);
        return response()->json([
            'success' => true,
            'supplier' => $supp
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $supp = Supplier::find($id);
        $validated = $request->validate([
            'supplier_name' => 'required|min:3|max:10',
            'phone' => 'required'
        ]);

        if($validated){
            $data = [
                'supplier_name' => $validated['supplier_name'],
                'phone' => $validated['phone']
            ];

            if($supp->update($data)){
                return response()->json([
                    'success' => true,
                    'supplier' => Supplier::find($id),
                    'message' => 'successfully updated'
                ]) ;
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Error'
                ]) ;
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $supp = Supplier::find($id);
        if($supp->delete()){
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
