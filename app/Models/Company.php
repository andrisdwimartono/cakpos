<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $table = 'companys';
    protected $fillable = ['company_name', 'company_email', 'company_email_password', 'address', 'company_id', 'company_id_label', 'user_creator_id', 'user_updater_id', 'updated_at'];

    function getCompany(){
        return $this->hasOne('App\Models\Company')->orderBy('no_seq', 'ASC');
    }
}
