<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $table = 'customers';
    protected $fillable = ['customer_name', 'photo_profile', 'company_id', 'id_card_number', 'first_name', 'last_name', 'email', 'phone_1', 'phone_2', 'segment_level', 'segment_level_label', 'member_level', 'member_level_label', 'user_creator_id', 'user_updater_id', 'updated_at'];

    function getSegment_level(){
        return $this->hasOne('App\Models\Segment_level')->orderBy('no_seq', 'ASC');
    }


    function getMember_level(){
        return $this->hasOne('App\Models\Member_level')->orderBy('no_seq', 'ASC');
    }
}
