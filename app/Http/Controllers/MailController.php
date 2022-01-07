<?php

namespace App\Http\Controllers;

use App\Models\FundingNode;
use App\Models\MerchantsData;
use App\Models\Transaction;
use App\Models\TransectionAlpha;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use DB;
use Session;

use App\Models\isEmailAllow;


class MailController extends Controller
{
    // function for email auto/manual
    public function isEmailAllow(Request $request){
        $type = $request->type;
        $userType = $request->userType;
        $emailStatus = $request->emailStatus;
        $isUpdate = DB::table('is_email_allow')->where('type', $type)->where('user_type', $userType)->update([
        'is_email'=>$emailStatus
            ]);
            
           if($isUpdate){
               if($emailStatus == 'auto'){
                   return 'auto';
               }else{
                   return 'manual';
               }
               
           }else{
               return 'no';
           }
    }
    
    /** function for daily manual report*/
    public function dailyManualEmail(Request $request){
        $customEmail = $request->dailyEmail;
        $arr = $request->searchIds;
        $searchIDs = explode(',',$arr[0]);
        $arryData = [];
        foreach($searchIDs as $ids){
           
            $transactionData = DB::table('transection_alphas')->where('id',$ids)->get();
            array_push($arryData, $transactionData);
        }
        $myArray = [
                [
                'Id', 'Transaction Label', 'Transaction AmountBTC', 'Transaction AmountUSD', 'Payment Hash', 'Conversion Rate', 'Payment Preimage',
                'Status', 'Merchant Id', 'Msatoshi', 'Destination', 'Description','Transaction Timestamp', 'Created At', 'Updated At'
                ]
            ];
        foreach ($arryData as $value) {
            foreach ($value as $key => $follower) {
                    unset($key);
                     array_push($myArray ,(array)$follower);
            }
        }
      $fp = fopen('file.csv', 'w');
      foreach ($myArray as $fields) {
          fputcsv($fp, $fields);
      }
      fclose($fp);
      
      
        $now = Carbon::now();
        $currentDate = $now->toDateString();
        
        $to_email = $customEmail;
        $data = ['currentDate'=>"$currentDate"];
        $doneEmail = Mail::send(['html'=>'email_templates.custom_report'], $data, function($message) use ($to_email) {
           $message->to($to_email)
               ->subject('C lightning Merchant daily');
           $message->from("nextlayertechnology@gmail.com");
          $message->attach('file.csv');
        //   $message->cc('ajmalg08@gmail.com');
        });
        
    return redirect('get-transactions-alpha')->with('message', 'Your Custom email has sent successfully');
    }
    
    /** function for sending the weekly manual report */
    public function weeklyManualEmail(Request $request){
        $weekMail = $request->weekMail;
        $weekStart = $request->weekStart;
        $weekEnd = $request->weekEnd;
        
        //  $datetime1 = strtotime($weekStart); // convert to timestamps
        // $datetime2 = strtotime($weekEnd); // convert to timestamps
        // $days = (int)(($datetime2 - $datetime1)/86400);
       
        // if($days > 7){
        //     return redirect()->back()->with('message', 'Please choose date less than or equal to 7 days');
        // }else{
        $dateS = new Carbon($weekStart);
        $dateE = new Carbon($weekEnd);
        $data = DB::table('transection_alphas')->whereBetween('created_at', [$dateS->format('Y-m-d')." 00:00:00", $dateE->format('Y-m-d')." 23:59:59"])->get();
       
        if($data->isEmpty()){
            return redirect()->back()->with('message', 'There is no record related to this dates');
        }
        
         $myArray = [
               [
                'Id', 'Transaction Label', 'Transaction AmountBTC', 'Transaction AmountUSD', 'Payment Hash', 'Conversion Rate', 'Payment Preimage',
                'Status', 'Merchant Id', 'Msatoshi', 'Destination', 'Description','Transaction Timestamp', 'Created At', 'Updated At'
                ]
            ];
        foreach($data as $object)
            {
                $myArray[] =  (array) $object;
            }
            
      $fp = fopen('file.csv', 'w');
      foreach ($myArray as $fields) {
          fputcsv($fp, $fields);
      }
      fclose($fp);
      
        $to_email = $weekMail;
        $data = ['weekStart'=> "$weekStart", 'weekEnd' => "$weekEnd"];
        $doneEmail = Mail::send(['html'=>'email_templates.weekly_manual_report'], $data, function($message) use ($to_email) {
           $message->to($to_email)
               ->subject('C lightning Merchant Weekly');
           $message->from("nextlayertechnology@gmail.com");
          $message->attach('file.csv');
        //   $message->cc('ajmalg08@gmail.com');
        });

    return redirect('get-transactions-alpha')->with('message', 'Your Weekly manual email has sent successfully');
        
       
        // }
    }
    
    
    // send email data for manual email
    public function manualEmail(Request $request){
        $eMail = $request->mEmail;
        $searchIDs =  json_decode($request->searchIDs, true);
        
         $arryData = [];
        foreach($searchIDs as $ids){
            $transactionData = DB::table('transactions')->where('id',$ids)->get();
            array_push($arryData, $transactionData);
        }
        $myArray = [
                [
                'Id', 'Transaction Label', 'Transaction Id', 'Transaction AmountBTC', 'Client Remaining', 'Merchan Remaining', 'Transaction AmountUSD',
                'Conversion Rate', 'Transaction ClientId', 'Transaction MerchantId', 'Transaction Timestamp', 'Created At', 'Updated At'
                ]
            ];
        foreach ($arryData as $value) {
            foreach ($value as $key => $follower) {
                    unset($key);
                     array_push($myArray ,(array)$follower);
            }
        }

      $fp = fopen('file.csv', 'w');
      foreach ($myArray as $fields) {
          fputcsv($fp, $fields);
      }
      fclose($fp);
      
 
        $to_email = $eMail;
        $data = array(['name'=>'this is name','body'=>'this is body']);
        $doneEmail = Mail::send('email_templates.manual_report', $data, function($message) use ($to_email) {
           $message->to($to_email)
               ->subject('C lightning Boost daily');
           $message->from("nextlayertechnology@gmail.com");
          $message->attach('file.csv');
        //   $message->cc('ajmalg08@gmail.com');
        });
    
       if($eMail){
            return $eMail;
        }
       
    }
    
    // sent data to manual email for alpha
    public function manualEmailAlpha(Request $request){
        $eMail = $request->mEmail;
        $searchIDs =  json_decode($request->searchIDs, true);
         $arryData = [];
        foreach($searchIDs as $ids){
            $transactionData = DB::table('transection_alphas')->where('id',$ids)->get();
            array_push($arryData, $transactionData);
        }
        $myArray = [
                [
                'Id', 'Transaction Label', 'Transaction AmountBTC', 'Transaction AmountUSD', 'Payment Hash', 'Conversion Rate', 'Payment Preimage',
                'Status', 'Merchant Id', 'Msatoshi', 'Destination', 'Description', 'Transaction Timestamp', 'Created At', 'Updated At'
                ]
            ];
        
        
        foreach ($arryData as $value) {
            foreach ($value as $key => $follower) {
                    unset($key);
                     array_push($myArray ,(array)$follower);
            }
        }

        $fp = fopen('file.csv', 'w');
      foreach ($myArray as $fields) {
          fputcsv($fp, $fields);
      }
      fclose($fp);
      
      
      /** check is the email process should be auto or manual */
    $emailAllow = DB::table('is_email_allow')->where('id', 2)->get();
    $isEmail = $emailAllow[0]->is_email;

if($isEmail == 'manual'){
        $to_name = "sisapps@stepinnsolution.com";
        $to_email = $eMail;
        $data = array(['name'=>'this is name','body'=>'this is body']);
        $doneEmail = Mail::send('email_templates.daily_beta', $data, function($message) use ($to_name, $to_email) {
           $message->to($to_email, $to_name)
               ->subject('C lightning Boost daily');
           $message->from("sisapps@stepinnsolution.com");
          $message->attach('file.csv');
        //   $message->cc('ajmalg08@gmail.com');
        });
       
      }
       if($eMail){
            return $eMail;
        }
       
    }

    //   Alpha dashBoard mails Autometic    
    
    public function daily_alpha_mails(){
    
               $merchants=TransectionAlpha::all();
                $uniq_data=$merchants->unique('merchant_id')->toArray();
               foreach ($uniq_data as $key=>$uniq){
                   $user_data[$key]['id']=$uniq['merchant_id'];
                   $user_data[$key]['email']=MerchantsData::where('merchant_name',$uniq['merchant_id'])->pluck('email')->first();
                 }
                    /* Checking if the monthly auto email is allow or not **/
                    $emailAllow = DB::table('is_email_allow')->where('user_type', 'alpha')->where('type', 'daily')->where('is_email', 'auto')->get();
                    $isEmail = $emailAllow[0]->is_email;
                    if($isEmail == "auto"){
            
            
               foreach ($user_data as $useremail){
                    if($useremail['email'] != null){
                   $data1=TransectionAlpha::orderBy('created_at', 'DESC')
                       ->where('merchant_id',$useremail['id'])
                       ->whereDate('created_at', '=', Carbon::today()->toDateString())->get()->toArray();
                   $fp = fopen('file.csv', 'w');
            
                   foreach ($data1 as $fields) {
                       fputcsv($fp, $fields);
                   }
                   fclose($fp);
                   
                    $now = Carbon::now();
                    $currentDate = $now->toDateString();
                   
                   $to_email = $useremail['email'];
                   $merchant_name = $useremail['id'];
                   $merchant_id = MerchantsData::where('merchant_name', $merchant_name)->pluck('id')->first();
                   $data = ['currentDate'=>"$currentDate", 'merchant_name'=> "$merchant_name",'merchant_id'=>"$merchant_id"];
                   Mail::send('email_templates.daily_alpha', $data, function($message) use ($to_email) {
                       $message->to($to_email)
                           ->subject('C lightning Boost daily');
                       $message->from("nextlayertechnology@gmail.com");
                       $message->attach('file.csv');
                   });
                }
            }
        }
      }
   
    public function weekly_alpha_mails(){
    
           $merchants=TransectionAlpha::all();
            $uniq_data=$merchants->unique('merchant_id')->toArray();
        //     dd($uniq_data);
           foreach ($uniq_data as $key=>$uniq){
               $user_data[$key]['id']=$uniq['merchant_id'];
               $user_data[$key]['email']=MerchantsData::where('merchant_name',$uniq['merchant_id'])->pluck('email')->first();
             }
    
             $emailAllow = DB::table('is_email_allow')->where('user_type', 'alpha')->where('type', 'weekly')->where('is_email', 'auto')->get();
             $isEmail = $emailAllow[0]->is_email;
             if($isEmail == "auto"){
        
           foreach ($user_data as $useremail){
                if($useremail['email'] != null){
               $data1=TransectionAlpha::orderBy('created_at', 'DESC')
                   ->where('merchant_id',$useremail['id'])
                   ->whereDate('created_at', '>', \Carbon\Carbon::now()->subWeek())->get()->toArray();
               $fp = fopen('file.csv', 'w');
        
               foreach ($data1 as $fields) {
                   fputcsv($fp, $fields);
               }
        
               fclose($fp);
               
               
               $now = Carbon::now();
                $currentDate = $now->toDateString();
                $week = $now->weekOfYear;
                $month = $now->month;
                $year = $now->year;
               
               $to_email = $useremail['email'];
               $merchant_name = $useremail['id'];
               $merchant_id = MerchantsData::where('merchant_name', $merchant_name)->pluck('id')->first();
               $data = ['currentDate'=>"$currentDate", 'merchant_id'=> $merchant_id, 'merchant_name' => "$merchant_name" ];
               Mail::send('email_templates.weekly_alpha', $data, function($message) use ($to_email) {
                   $message->to($to_email)
                       ->subject('C lightning Boost Weekly');
                   $message->from("nextlayertechnology@gmail.com");
                   $message->attach('file.csv');
               });
                }
           }
           }
          }
    
    public function monthly_alpha_mails(){
    
          $merchants=TransectionAlpha::all();
            $uniq_data=$merchants->unique('merchant_id')->toArray();
           foreach ($uniq_data as $key=>$uniq){
               $user_data[$key]['id']=$uniq['merchant_id'];
               $user_data[$key]['email']=MerchantsData::where('merchant_name',$uniq['merchant_id'])->pluck('email')->first();
             }
         $emailAllow = DB::table('is_email_allow')->where('user_type', 'alpha')->where('type', 'monthly')->where('is_email', 'auto')->get();
                $isEmail = $emailAllow[0]->is_email;
                if($isEmail == "auto"){
        
           foreach ($user_data as $useremail){
                if($useremail['email'] != null){
               $data1=TransectionAlpha::orderBy('created_at', 'DESC')
                   ->where('merchant_id',$useremail['id'])
                   ->whereDate('created_at', '>', \Carbon\Carbon::now()->subMonth())->get()->toArray();
               $fp = fopen('file.csv', 'w');
        
               foreach ($data1 as $fields) {
                   fputcsv($fp, $fields);
               }
        
               fclose($fp);
               
                  $now = Carbon::now();
                $currentDate = $now->toDateString();
                $week = $now->weekOfYear;
                $month = $now->format('F');
                $year = $now->year;
               
               $to_email = $useremail['email'];
               $merchant_name = $useremail['id'];
               $merchant_id = MerchantsData::where('merchant_name', $merchant_name)->pluck('id')->first();
               $data = ['month'=>"$month",'year'=>"$year", 'merchant_id'=> $merchant_id, 'merchant_name'=>$merchant_name];
               Mail::send('email_templates.monthly_alpha', $data, function($message) use ($to_email) {
                   $message->to($to_email)
                       ->subject('C lightning Boost Monthly');
                   $message->from("nextlayertechnology@gmail.com");
                   $message->attach('file.csv');
               });
             }
            }
          }
        }
    
    
    
  
}
