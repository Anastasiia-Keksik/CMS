<?php


namespace App\Services;


class Kalk
{
    //potegowanie
    public function pow($liczba, $potega){
        $wynik = 1;
        for($i=0;$i<$potega;$i++)
        {
            $wynik = $wynik*$liczba;
        }
        return $wynik;
    }

}