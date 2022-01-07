<?php

namespace App\Http\Controllers;

use App\Models\MerchantsData;
use App\Models\TransectionAlpha;
use Illuminate\Support\Facades\Auth;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $merchant_id=MerchantsData::where('user_id',auth()->user()->id)->first()->merchant_id;
        $merchant_Transection=TransectionAlpha::where('merchant_id',$merchant_id)->count();
        return view('dashboard',compact('merchant_Transection'));
    }
}
