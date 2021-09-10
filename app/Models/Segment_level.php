<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Segment_level extends Model
{
    use HasFactory;
    protected $table = 'segment_levels';
    protected $fillable = ['segment_level_name', 'company_id', 'discount_percentage', 'discount', 'user_creator_id', 'user_updater_id', 'updated_at'];
}
