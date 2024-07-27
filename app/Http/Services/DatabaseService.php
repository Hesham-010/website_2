<?php

namespace App\Http\Services;

class DatabaseService
{
    public function getConnectionBasedCountry($country)
    {
        if ($country == 'eg') {
            $connection = 'eg2';
        }else if ($country == 'sa') {
            $connection = 'sa2';
        }else if ($country == 'in') {
            $connection = 'in2';
        }

        return $connection;
    }
}