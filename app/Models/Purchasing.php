<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchasing extends Model
{
    use HasFactory;
    protected $table = 'purchasings';
    protected $fillable = ['purchasing_name', 'company_id', 'purchasing_datetime', 'buying_datetime', 'supplier', 'supplier_label', 'purchasing_detail_total', 'purchasing_discount_percentage', 'purchasing_discount_total', 'purchasing_total', 'is_paynow', 'buying_total', 'change_total', 'purchasing_status', 'user_creator_id', 'user_updater_id', 'updated_at'];

    function getSupplier(){
        return $this->hasOne('App\Models\Supplier')->orderBy('no_seq', 'ASC');
    }


    function getPurchasing_detail(){
        return $this->hasMany('App\Models\Purchasing_detail')->orderBy('no_seq', 'ASC');
    }

    function getPayment_detail(){
        return $this->hasMany('App\Models\Payment_detail')->orderBy('no_seq', 'ASC');
    }
}
