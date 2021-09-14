<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    protected $table = 'suppliers';
    protected $fillable = ['supplier_name', 'company_id', 'first_name', 'last_name', 'supplier_company', 'email', 'phone_1', 'phone_2', 'photo_profile', 'user_creator_id', 'user_updater_id', 'updated_at'];
}
