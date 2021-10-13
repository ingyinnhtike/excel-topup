<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Retry extends Model
{
    protected $fillable = ['name','processed','succeeded','failed','total','status','user_id','service_id'];
}
