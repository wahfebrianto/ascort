<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = "products";
    protected $fillable = ['product_code', 'product_name'];

    // ---------------------------------------------- RELATIONSHIP --------------------------------------------------

    public function sales(){
        return $this->hasMany('App\Sale', 'product_id', 'id');
    }

    // ------------------------------------------ END OF RELATIONSHIP -----------------------------------------------

    public function getSalesAttribute(){
        return $this->sales()->where('is_active', '=', 1)->get();
    }

    public static function getProductFromId($id){
        $found = Product::where('id', '=', $id)->where('is_active', '=', 1)->get();
        return (count($found)>0 ? $found[0] : null);
    }

    public static function getProducts(){
        return Product::where('is_active', '=', 1)->get();
    }

    public static function getProducts_ForDropDown(){
        $products = [];
        $allProducts = Product::getProducts();
        foreach($allProducts as $a){
            $products[$a->id] = $a->product_name;
        }
        return $products;
    }

}
