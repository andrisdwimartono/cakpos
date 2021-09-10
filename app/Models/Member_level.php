<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member_level extends Model
{
    use HasFactory;
    protected $table = 'member_levels';
    protected $fillable = ['member_level_name', 'company_id', 'discount_percentage', 'discount', 'user_creator_id', 'user_updater_id', 'updated_at'];
}
