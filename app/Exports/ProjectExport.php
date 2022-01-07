<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB;

class ProjectExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    

 public function collection()
    {
        
        $merchant_id = auth()->user()->merchant_id;
        $data = DB::table('transection_alphas')->where('merchant_id' , $merchant_id)->get();
        return $data;
    }
    public function headings(): array
    {
        return ["id", "transaction_label", "transaction_amountBTC","transaction_amountUSD", "payment_hash", "conversion_rate","payment_preimage", "status","merchant_id" ,"msatoshi","destination", "description", "transaction_timestamp" , "created_at" , "updated_at"];
    }
    


}
