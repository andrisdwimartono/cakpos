<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchasing_detail extends Model
{
    use HasFactory;
    protected $table = 'purchasing_details';
    protected $fillable = ['parent_id', 'no_seq', 'product', 'product_label', 'purchasing_price', 'quantity', 'discount_percentage', 'discount_total', 'total', 'warehouse', 'warehouse_label', 'user_creator_id', 'user_updater_id', 'updated_at'];

    function getPurchasing(){
        return $this->hasOne('App\Models\Purchasing');
    }
}
