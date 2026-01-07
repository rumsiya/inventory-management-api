<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products =Product::with('getSupplier')
                    ->with('getCategory')
                    ->with('getUnit')->get();
        return response()->json([
            'success' => true,
            'products' => $products
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

        $validated = $request->validate([
            'product_name' => 'required|min:3|max:20',
            'quantity' => 'required|numeric',
            'price' => 'required|decimal:2',
            'unit_id' => 'required|numeric',
            'category_id' => 'required|numeric',
            'supplier_id' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,webp'
        ]);




        if($validated){
            // $image = $request->file('image');
            // $imagePath = $image->store('assets','public');

                        $imageUrl = null;

                    if ($request->hasFile('image')) {
                        $upload = cloudinary()->upload(
                            $request->file('image')->getRealPath()
                        );
                        $imageUrl = $upload->getSecurePath();
            }

            $data = [
                'product_name' => $validated['product_name'],
                'quantity' => $validated['quantity'],
                'price' => $validated['price'],
                'unit_id' => $validated['unit_id'],
                'category_id' => $validated['category_id'],
                'supplier_id' => $validated['supplier_id'],
                'image' => $imagePath
            ];
            $product = Product::create($data);
            if($product){
                return response()->json([
                    'success' => true,
                    'product' => $product->load('getSupplier')->load('getUnit')->load('getCategory'),
                    'message' => 'successfully product generated'
                ]);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'failed creation'
                ]);

            }
        }

           //code...
        } catch (\Exception $e) {
            return $e->getMessage();
        }



    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::find($id);
        return response()->json([
            'success' => true,
            'product' => $product
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {

        $product = Product::find($id);
        $validated = $request->validate([
            'product_name' => 'required|min:3|max:20',
            'quantity' => 'required|numeric',
            'price' => 'required',
            'unit_id' => 'required|numeric',
            'category_id' => 'required|numeric',
            'supplier_id' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,jpg,png'
        ]);




        if($validated){

            if ($request->hasFile('image')) {
            $upload = cloudinary()->upload(
                $request->file('image')->getRealPath()
            );
            $imageUrl = $upload->getSecurePath();
        }

            // $image = $request->file('image');
            // $imagePath = $image->store('assets','public');
            $data = [
                'product_name' => $validated['product_name'],
                'quantity' => $validated['quantity'],
                'price' => $validated['price'],
                'unit_id' => $validated['unit_id'],
                'category_id' => $validated['category_id'],
                'supplier_id' => $validated['supplier_id'],
                'image' => $imagePath
            ];

            if($product->update($data)){
                return response()->json([
                    'success' => true,
                    'product' => Product::with('getUnit')->with('getCategory')->with('getSupplier')->find($id),
                    'message' => 'successfully product updated'
                ]);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'failed creation'
                ]);

            }
        }

           //code...
        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);
        if($product->delete()){
            return response()->json([
                'success' => true,
                'message' => 'successfully deleted'
            ]);
        }else{
             return response()->json([
                'success' => false,
                'message' => 'Error found'
            ]);
        }
    }
}
