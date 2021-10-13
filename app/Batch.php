<?php

namespace App;

use Aws\Api\Service;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    protected $fillable = ['name','processed','succeeded','failed','total','status','user_id','package_id','service_id','customer_id'];

    public function billrequests()
    {
        return $this->hasMany(BillRequest::class);
    } 
}
