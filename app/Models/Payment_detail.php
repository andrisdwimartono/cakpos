<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment_detail extends Model
{
    use HasFactory;
    protected $table = 'payment_details';
    protected $fillable = ['parent_id', 'no_seq', 'payment_type', 'paying_method', 'paying_method_label', 'paying', 'payment_notes', 'user_creator_id', 'user_updater_id', 'updated_at'];

    function getSelling(){
        return $this->hasOne('App\Models\Selling');
    }
}
