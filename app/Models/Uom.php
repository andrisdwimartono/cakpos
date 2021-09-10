<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Uom extends Model
{
    use HasFactory;
    protected $table = 'uoms';
    protected $fillable = ['uom_name', 'company_id', 'description', 'user_creator_id', 'user_updater_id', 'updated_at'];
}