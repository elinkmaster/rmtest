<?php

namespace App\Helpers;

class UtilityHelper
{
    public static function hasTotalString(array $data)
    {
        if(!str_contains($data['title'], 'Total'))
        {
            return false;
        }

        return true;
    }
}
