<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['name','amount','customer_id'];

    public function customer()
    {
        return $this->belongsTo(Customer::class,'customer_id');
    }

}
