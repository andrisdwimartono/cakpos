<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Selling extends Model
{
    use HasFactory;
    protected $table = 'sellings';
    protected $fillable = ['selling_name', 'company_id', 'selling_datetime', 'paying_datetime', 'customer', 'customer_label', 'selling_detail_total', 'selling_discount_percentage', 'selling_discount_total', 'selling_total', 'is_paynow', 'paying_total', 'change_total', 'selling_status', 'user_creator_id', 'user_updater_id', 'updated_at'];

    function getCustomer(){
        return $this->hasOne('App\Models\Customer')->orderBy('no_seq', 'ASC');
    }


    function getSelling_detail(){
        return $this->hasMany('App\Models\Selling_detail')->orderBy('no_seq', 'ASC');
    }

    function getPayment_detail(){
        return $this->hasMany('App\Models\Payment_detail')->orderBy('no_seq', 'ASC');
    }
}
