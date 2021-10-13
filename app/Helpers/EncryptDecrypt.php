<?php

namespace App\Helpers;

class EncryptDecrypt
{
    static $key = "5zAykHkbauwWQP5eR1ZqCBftAlFHx3vSIcScLqzyaeQ";

    public static function myEncrypt($value)
    {
        $key = static::$key;

        $iiv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));

        $data_encrypted = openssl_encrypt($value, 'aes-256-cbc', $key, 0, $iiv);

        $encode_encrypted_data = base64_encode($data_encrypted . '::' . $iiv);

        return $encode_encrypted_data;
    }


    public static function myDecrypt($encrypted)
    {
        $key = static::$key;

        list($encrypted_data, $iv) = explode('::', base64_decode($encrypted), 2);

        $decrypt_data = openssl_decrypt($encrypted_data, 'aes-256-cbc', $key, 0, $iv);

        return $decrypt_data;
    }
}
