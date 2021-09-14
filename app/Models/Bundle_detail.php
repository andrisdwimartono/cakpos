<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bundle_detail extends Model
{
    use HasFactory;
    protected $table = 'bundle_details';
    protected $fillable = ['parent_id', 'no_seq', 'company_id', 'product', 'product_label', 'quantity', 'selling_price', 'discount_percentage', 'discount_total', 'total', 'user_creator_id', 'user_updater_id', 'updated_at'];

    function getBundle(){
        return $this->hasOne('App\Models\Bundle');
    }
}
