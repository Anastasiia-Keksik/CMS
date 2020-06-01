<?php


namespace App\Services;


class GlobalService
{
    public function getGlobal($variable)
    {
        return $_SERVER[$variable];
    }
}