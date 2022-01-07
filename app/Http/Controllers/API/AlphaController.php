<?php

namespace App\Http\Controllers\API;
use DB;

use App\Http\Controllers\Controller;
use App\Models\TransectionAlpha;
use Illuminate\Http\Request;
use carbon\carbon;

class AlphaController extends Controller
{
    public function addAlphaTransction(Request $request){
        
        
        $check = DB::table('merchants_data')->where('merchant_id' , $request['merchant_id'])->first();
        if(!isset($check) || $check == null){
            return response()->json(['message'=>'merchant not found','status'=>403]);
        }
        
        $data = DB::table('transection_alphas')->insert([
        'transaction_label'=>$request['transaction_label'],
        'payment_hash'=>$request['payment_hash'],
        'transaction_amountBTC'=>$request['transaction_amountBTC'],
        'transaction_amountUSD'=>$request['transaction_amountUSD'],
        'payment_preimage'=>$request['payment_preimage'],
        'status'=>$request['status'],
        'destination'=>$request['destination'],
        'transaction_timestamp'=>$request['transaction_timestamp'],
        'msatoshi'=>$request['msatoshi'],
        'conversion_rate'=>$request['conversion_rate'],
        'merchant_id'=>$request['merchant_id'],
        'description' => $request['transaction_description'],
        'created_at' => carbon::now(),
        'updated_at' => carbon::now(),
            ]);
            
        $data = DB::table('transection_alphas')->orderby('id','desc')->first();

        
        if($data){
            return response()->json(['message'=>'successfully done','data'=>$data]);
        }else{
            return response()->json(['message'=>'some thing went wrong'] );
        }
    }

}
