<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransectionAlpha extends Model
{
    use HasFactory;
      protected $fillable=['transaction_label','status','transaction_amountBTC','transaction_amountUSD','msatoshi',
    'payment_preimage','transaction_timestamp','destination','payment_hash','conversion_rate','merchant_id', 'description'];
}
