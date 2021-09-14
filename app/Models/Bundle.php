<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bundle extends Model
{
    use HasFactory;
    protected $table = 'bundles';
    protected $fillable = ['bundle_name', 'company_id', 'bundle_code', 'total_price', 'discount_percentage_bundle', 'discount_total_bundle', 'total_bundle', 'user_creator_id', 'user_updater_id', 'updated_at'];

    function getBundle_detail(){
        return $this->hasMany('App\Models\Bundle_detail')->orderBy('no_seq', 'ASC');
    }
}
