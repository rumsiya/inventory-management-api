<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return response()->json([
            'success' => true,
            'categories' => $categories
        ]) ;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            //code...

            $validated = $request->validate([
                'category_name' => 'required|min:3|max:20'
            ]);

            if($validated){
                $data = [
                    'category_name' => $validated['category_name']
                ];

                $cat = Category::create($data);
                if($cat){
                    return response()->json([
                        'success' => true,
                        'categories'=> $cat,
                        'message' => 'succesfully created'
                    ]) ;
                }else{
                return response()->json([
                        'success' => false,
                        'message' => 'Failed'
                    ]) ;
                }
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $categories = Category::find($id);
        return response()->json([
            'success'=> true,
            'categories' => $categories
        ]) ;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $categories = Category::find($id);
        $validated = $request->validate([
                'category_name' => 'required|min:3|max:20'
        ]);
        if($validated){
            $data = [
                'category_name' => $validated['category_name']
            ];
            $cat =$categories->update($data);
            if($cat){
                return response()->json([
                    'success' => true,
                    'categories' => Category::find($id),
                    'message' => 'successfully updated'
                ]);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Failed updation'
                ]);
            }
        }


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cat = Category::find($id);
        if($cat->delete()){
            return response()->json([
                'success' => true,
                'message' => 'deleted successfully'
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'failed deletion'
            ]);
        }
    }
}
