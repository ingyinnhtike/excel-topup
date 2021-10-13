<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DataRequest extends Model
{
    protected $fillable = ['reference_id','phone_number','provider','operator','package_id','batch_id','status','merchant','user_id','service_id'];

    public function dataBatch()
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }
}
