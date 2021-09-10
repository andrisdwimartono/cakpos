<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product_stock extends Model
{
    use HasFactory;
    protected $table = 'product_stocks';
    protected $fillable = ['parent_id', 'no_seq', 'company_id', 'warehouse', 'warehouse_label', 'stock', 'user_creator_id', 'user_updater_id', 'updated_at'];

    function getProduct(){
        return $this->hasOne('App\Models\Product');
    }
}
