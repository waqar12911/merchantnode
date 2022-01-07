<?php

namespace App\Http\Controllers;

use App\Models\MerchantsData;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MerchantsDataController extends Controller
{
    
    
    public function addMerchant(){

    return view('users.add-merchant');
    }
    
    
    public function createMerchant(Request $request){
        // dd($request);
        $data=MerchantsData::create([
            "merchant_name"=>$request['merchant_name'],
            "maxboost_limit"=>$request['maxboost_limit'],

            "merchant_maxboost"=>$request['merchant_maxboost'],
            
            "password"=>$request['password'],
            "store_name"=>$request['store_name'],
            "email"=>$request['email'],
            "ssh"=>$request['ssh'],
            "pannel_password"=>$request['pannel_password'],
            "latitude"=>$request['latitude'],
            "longitude"=>$request['longitude'],
        ]);
        if($data){
            $data2= User::create([
            // "merchant_id"=>$data->id,
            "merchant_id"=>$request['merchant_name'],
            "name"=>$request['merchant_name'],
            "email"=>$request['email'],       
            "type"=>'alpha',
            "password"=>Hash::make($request['pannel_password']),
            
            ]);
        }
        if($data2){
            return redirect()->route('user.index')->with('message','Merchant Created successfully');
        }else{
            return redirect()->route('user.index')->with('message','Something Went successfully');
        }
        
        // return redirect()->route('user.index')->with('message','Merchant Created successfully');
    }
    
    
    public function editMerchant($id){
        $data=MerchantsData::where('id',$id)->get()->first();

        return view('users.edit-merchant',compact('data'));
    }
    
    
    public function updateMerchant(Request $request,$id){
        $data=MerchantsData::where('id',$id)->update([
           "merchant_name"=>$request['merchant_name'],
            "maxboost_limit"=>$request['maxboost_limit'],
            
            "merchant_maxboost"=>$request['merchant_maxboost'],
            
            "password"=>$request['password'],
            "store_name"=>$request['store_name'],
            "email"=>$request['email'],
            "ssh"=>$request['ssh'],
            "pannel_password"=>$request['pannel_password'],
            "latitude"=>$request['latitude'],
            "longitude"=>$request['longitude'],
        ]);
        
         if($data){
            $data2= User::where('merchant_id',$id)->update([
            "type"=>'alpha',
            "name"=>$request['email'],
            "email"=>$request['merchant_name'],       
            "password"=>Hash::make($request['pannel_password']),
            ]);
        }
        if($data2){
            return redirect()->route('user.index')->with('message','Merchant Updated successfully');
        }else{
            return redirect()->route('user.index')->with('message','Something Went Wrong');
        }
    }
    
    public function merchantDelete($id){
        $data=MerchantsData::where('id',$id)->delete();
        if($data){
            return redirect()->back()->with('message','Deleted Successfully');
        }
    }
    
    
    
    
}
