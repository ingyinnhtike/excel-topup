<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = ['operator','package_name','package_code','volume','price','customer_id','user_id','deleted_at'];
}
