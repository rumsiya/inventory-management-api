<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StockTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use App\Models\Product;

class StockTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stocks = StockTransaction::with('getProduct')->with('getUnit')->get();
        return response()->json([
            'success' => true,
            'stocks' => $stocks
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            //code...

            $validated= $request->validate([
                'product_id' =>'required|numeric',
                'quantity' => 'required',
                'unit_id' => 'required',
                'type' => 'required',
                'user_id' => 'nullable'
            ]);

            if($validated){

                $data = [
                    'product_id' => $validated['product_id'],
                    'quantity' => $validated['quantity'],
                    'unit_id' => $validated['unit_id'],
                    'type' => $validated['type'],
                    'user_id' =>auth()->user()->id
                ];



                $stocks = StockTransaction::create($data);
                if($stocks){
                    $stockNo = 'STK-'.$stocks['id'].'-'.strtoupper(Str::random(8));
                    StockTransaction::where('id',$stocks['id'])
                        ->update([
                            'stock_id' =>  $stockNo

                        ]);
                    $product = Product::find($validated['product_id']);
                    if($validated['type'] == 'IN'){
                        $product->quantity += $validated['quantity'];

                    }
                    else if($validated['type'] == 'OUT'){
                        if($validated['quantity'] > $product->quantity){
                            DB::rollBack();
                        }else{

                             $product->quantity -=  $validated['quantity'];
                        }
                    }
                    $product->save();
                    DB::commit();
                    return response()->json([
                        'success' => true,
                        'stocks' => StockTransaction::with('getProduct')->with('getUnit')->find($stocks['id']),
                        'message' =>'successfully saved'
                    ]);
                }else{
                    return response()->json([
                        'success' => false,
                        'message' =>'Failed '
                    ]);
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
            //throw $th;
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $stock = StockTransaction::find($id);
        return response()->json([
            'success' => true,
            'stock' => $stock
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getReports(){
         $stocks =  StockTransaction::with(['getProduct', 'getUnit'])
            ->select(
                DB::raw("DATE_FORMAT(created_at, '%b') as month"),
                DB::raw('SUM(quantity) as total_quantity')
            )
            ->groupBy( 'month')
            ->orderBy('month')
            ->get();
        return response()->json([
            'success' => true,
            'stocks' => $stocks
        ]);
    }


}
