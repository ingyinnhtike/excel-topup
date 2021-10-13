<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BillRequest extends Model
{
    protected $fillable = ['reference_id','phone_number','provider','operator','batch_id','status','merchant','user_id','service_id'];

    public function batch()
    {
        return $this->belongsTo(Batch::class,'batch_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class,'customer_id');
    }
   
}
