<?php

namespace App\Http\Controllers;

use App\Models\MerchantsData;
use App\Models\User;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Swift_Mailer;
use Swift_SmtpTransport;
use Mail;
use DB;
use Auth;
use Session;

class UserController extends Controller
{
    /**
     * Display a listing of the users
     *
     * @param  \App\Models\User  $model
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\View\View
     */
    public function reset_link(User $model)
    {
        return view('auth.passwords.email');
    }
     public function admin_login(User $model)
    {
        
        return view('users.login');
    }
 public function do_login(Request $request)
    {

        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        
         $userdata = DB::table('users')->where('email',$request->email)->where('verify_email_code',$request->verify_email_code)->first();
        //$userdata = DB::table('users')->where('email',$request->email)->first();
       
        if($userdata)
        {  
            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials)) 
            {
                return redirect()->intended('home');
            }
        }
        else
        {
            $email = $request->email;
            $password = $request->password;
            return view('users.verify',compact('email','password'))->withErrors(['Wrong code entered']);
        }


      
    }
    public function do_verify(Request $request)
    {

        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $user = DB::table('users')->where('type','alpha')->where('email',$request->email)->first();

        if($user && Hash::check($request->password, $user->password)) 
        {
                $transport = new Swift_SmtpTransport('mail.nextlayer.live', 587, 'tls');
            $transport->setUsername('outgoing@nextlayer.live');
            $transport->setPassword('Bitcoin2020$');
                $swift_mailer = new Swift_Mailer($transport);
                Mail::setSwiftMailer($swift_mailer);
                $data = ["name" => "Qaiser" , "email"=>"qaiser@stepinnsolution.com"];
                 $reciever_email = $request->email;
                 $sender_email = 'outgoing@nextlayer.live';
                 $subject = 'Merchant Panel Verification Code';
                

                $data['verify_email_code'] = $this->RandomString();
                DB::table('users')->where('email',$request->email)->update([
                   "verify_email_code" => $data['verify_email_code']
                   ]);

                Mail::send(['html'=>'emails.reset_password'], $data, function($message) use($reciever_email , $sender_email, $subject ) {
                    $message->to($reciever_email, 'Nextlayer Sovereign')->subject
                    ($subject);
                    $message->from($sender_email,$sender_email);
                });
                $email = $request->email;
                $password = $request->password;
                $error = '';
                return view('users.verify',compact('email','password','error'));


        }
        else
        {
            return redirect()->back()->withErrors(['Email / Password was incorrect']);
        }
        
    }
    public function reset_password(Request $request)
    {
        $userdata = DB::table('users')->where('email',$request->email)->first();

        if($userdata){
        }else{ 
            return redirect()->back()->with('error','Email does not match');
        }
         $transport = new Swift_SmtpTransport('mail.nextlayer.live', 587, 'tls');
            $transport->setUsername('outgoing@nextlayer.live');
            $transport->setPassword('Bitcoin2020$');

                $swift_mailer = new Swift_Mailer($transport);
                Mail::setSwiftMailer($swift_mailer);
                 $reciever_email = $request->email;
                 $sender_email = 'outgoing@nextlayer.live';
                 $subject = 'Merchant Panel Forgot Password Code';
                

                $data['verify_email_code'] = $this->RandomString();
                DB::table('users')->where('email',$request->email)->update([
                   "verify_email_code" => $data['verify_email_code']
                   ]);

                Mail::send(['html'=>'emails.reset_code'], $data, function($message) use($reciever_email , $sender_email, $subject ) {
                    $message->to($reciever_email, 'Nextlayer Sovereign')->subject
                    ($subject);
                    $message->from($sender_email,$sender_email);
                });
                Session::put('email',$reciever_email);
                return redirect('change-password')->with('success','Check your email for one time password');;
    }
    public function RandomString()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randstring = '';
        for ($i = 0; $i < 5; $i++) {
            $randstring .= $characters[rand(0, strlen($characters))];
        }
        return strtoupper($randstring);
    }
    public function update_form()
    {
        return view('auth.passwords.reset');
    }
    public function update(Request $request)
    {

          $request->validate([
            'code' => 'required',
            'password' => 'required|min:6|string|same:password_confirmation',
        ]);

          $userdata = DB::table('users')->where('email',$request->email)->where('verify_email_code',$request->code)->first();
        if($userdata)
        {  
            DB::table('users')->where('email',$request->email)->where('verify_email_code',$request->code)->update([
                'password' => Hash::make($request->password),
                'verify_email_code' =>'',
            ]);
            return redirect('admin-login')->with('success','Password updated you can now login');
        }
        else
        {
            return redirect()->back()->with('error','One time password does not match/expire');
        }

    }


}
