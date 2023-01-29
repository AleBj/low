<?php
class Encriptar {
 
    private static $Key = "bartelotto";
 
    public static function encrypt ($input) {
        $output = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5(encriptar::$Key), $input, MCRYPT_MODE_CBC, md5(md5(encriptar::$Key))));
        return $output;
    }
 
    public static function decrypt ($input) {
        $output = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5(encriptar::$Key), base64_decode($input), MCRYPT_MODE_CBC, md5(md5(encriptar::$Key))), "\0");
        return $output;
    }
 
}