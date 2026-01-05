<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Unit;
use App\Models\Category;
use App\Models\Supplier;

class Product extends Model
{
    protected $table ="products";
    protected $fillable = ['product_name','quantity','price','unit_id','category_id','supplier_id','image'];

    public function getUnit(){
        return $this->belongsTo(Unit::class,'unit_id','id');
    }

    public function getCategory(){
        return $this->belongsTo(Category::class,'category_id','id');
    }

     public function getSupplier(){
        return $this->belongsTo(Supplier::class,'supplier_id','id');
    }

}
