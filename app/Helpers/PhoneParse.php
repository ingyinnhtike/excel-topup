<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class PhoneParse
{  
    public static function getOperator($phoneNo)
    {
        $prefix = substr($phoneNo, 3, 2);

        if ($prefix >= '74' && $prefix <= '79') {
            $operator = "Telenor";  //Telenor
        } elseif ($prefix >= '90' && $prefix <= '99') {
            $operator = "Ooredoo";  //Ooredoo
        }elseif ($prefix >= '65' && $prefix <= '69') {
            $operator = "MyTel"; //MyTel
        } else {
            $operator = "MPT"; //MPT
        }

        return $operator;
    }
  

    public static function parseBillAmount($number)
    {
        return $number;
    }

}
