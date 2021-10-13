<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['name', 'keyword', 'token', 'phone_number', 'logo', 'address', 'bill', 'data', 'user_id', 'added_user_id'];

    public function billrequest()
    {
        return $this->belongsTo(BillRequest::class, 'customer_id');
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'added_user_id');
    }
}
