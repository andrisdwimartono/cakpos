<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $fillable = ['product_name', 'product_photo', 'company_id', 'produce_code', 'uom', 'uom_label', 'category', 'category_label', 'buying_price', 'selling_price', 'discount_percentage', 'discount', 'status', 'status_label', 'user_creator_id', 'user_updater_id', 'updated_at'];

    function getUom(){
        return $this->hasOne('App\Models\Uom')->orderBy('no_seq', 'ASC');
    }

    function getCategory(){
        return $this->hasOne('App\Models\Category')->orderBy('no_seq', 'ASC');
    }

    function getProduct_stock(){
        return $this->hasMany('App\Models\Product_stock')->orderBy('no_seq', 'ASC');
    }
}
