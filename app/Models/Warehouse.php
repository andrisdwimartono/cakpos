<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;
    protected $table = 'warehouses';
    protected $fillable = ['warehouse_name', 'company_id', 'address_1', 'address_2', 'description', 'user_creator_id', 'user_updater_id', 'updated_at'];
}
