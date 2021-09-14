<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Selling_detail extends Model
{
    use HasFactory;
    protected $table = 'selling_details';
    protected $fillable = ['parent_id', 'no_seq', 'product_or_bundle', 'product_or_bundle_label', 'is_bundle', 'product', 'product_label', 'bundle', 'bundle_label', 'selling_price', 'quantity', 'discount_percentage', 'discount_total', 'total', 'warehouse', 'warehouse_label', 'user_creator_id', 'user_updater_id', 'updated_at'];

    function getSelling(){
        return $this->hasOne('App\Models\Selling');
    }
}
