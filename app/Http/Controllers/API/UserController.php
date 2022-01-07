<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\MerchantsData;
use App\Models\User;
use Illuminate\Http\Request;
use DateTime;
use DB;
use Illuminate\Support\Facades\Hash;
use function PHPUnit\Framework\isEmpty;

class UserController extends Controller
{
    public $successStatus = 200;
    /**
     * login api
     *
     * @return \Illuminate\Http\JsonResponse
     */

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function merchants_login(Request $request)
    {
        $user =MerchantsData::where('merchant_id',$request->merchant_id)->where('merchant_backend_password',$request->merchant_password)->get();
        if($user)
        {
            if($user->count()<1)
            {
                return response()->json(['message' =>'invelid User']);
            }
            return response()->json(['message'=>'successfully done','data'=>$user] );
        }
        else
        {
            return response()->json(['message'=>'somthing went wrong'] );
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function upload(Request $request)
    {
        $result = $request->file('file')->store('apiDocument');
        return ["result" => $result];
    }


    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function merchants_Edit(Request $request,$id){
        $user =MerchantsData::where('id',$id)->update([
            "merchant_maxboost"=>$request['merchant_maxboost'],
        ]);

        if ($user){
            $data =MerchantsData::where('id',$id)->get()->first();
            return response()->json(['message'=>'updated successfully','data'=>$data] );
            }else{
            return response()->json(['message'=>'Some thing went wrong'] );
        }

    }

    public function merchant_maxboost()
    {
       $user =MerchantsData::all();
            foreach($user as $data){
                $maxlimit=$data->maxboost_limit;
                $id=$data->id;
                MerchantsData::where('id',$id)->Update([
                    'merchant_maxboost'=>$maxlimit??'0',
                ]);
            }

    }

    public function getMerchants(){

        $data=MerchantsData::all();

        if($data){
        return response()->json(['message'=>'successfully done','data'=>$data] );
        }
    }
    public function checkMerchant(Request $request){
            
            $merchant_id=$request['merchant_id'];
            $merchant_pass=$request['password'];
            $data=MerchantsData::where('merchant_id',$merchant_id)
                                ->where('password',$merchant_pass)->get();
                                
            if($data->count() > 0){
                return response()->json(['message'=>'successfully done','data'=>$data] );
            }else{
                return response()->json(['message'=>'somthing went wrong'] );
            };
            
    }




}
