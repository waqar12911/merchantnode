<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MerchantsData extends Model
{
    use HasFactory;
    protected $fillable=['merchant_name','maxboost_limit','store_name','password','email','ssh','latitude','longitude','merchant_maxboost','pannel_password'];
}
