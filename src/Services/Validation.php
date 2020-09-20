<?php


namespace App\Services;


class Validation
{
    public function password($value){
        if (strlen($value) > 64){
            return 'Password is too long. Maximal char length equal 64.';
        }

        if (preg_match("/[^A-Za-z0-9]/", $value)) {
            return 'Password contains illegal characters, only A-Z,a-z and 0-9 are allowed.';
        }

        return true;
    }

    public function string255($value){
        if (strlen($value) > 64){
            return ' is too long. Maximal char length equal 255.';
        }

        if (preg_match("/[^A-Za-z0-9 ]/", $value)) {
            return ' contains illegal characters, only A-Z, a-z, 0-9 and one space between words are allowed.';
        }

        return true;
    }
}