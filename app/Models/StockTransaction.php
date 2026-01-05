<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\Unit;


class StockTransaction extends Model
{
    protected $table ="stock_transactions";
    protected $fillable = ['stock_id','product_id','type','unit_id','quantity','user_id'];

    public function getProduct(){
        return $this->belongsTo(Product::class,'product_id','id');
    }

    public function getUnit(){
        return $this->belongsTo(Unit::class,'unit_id','id');
    }

}
