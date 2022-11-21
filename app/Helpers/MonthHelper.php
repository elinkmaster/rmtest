<?php

namespace App\Helpers;

use Faker\Core\Number;

class MonthHelper {

    private static $MONTHS = [
        1 => 'January',
        2 => 'February',
        3 => 'March',
        4 => 'April',
        5 => 'May',
        6 => 'June',
        7 => 'July',
        8 => 'August',
        9 => 'September',
        10 => 'October',
        11 => 'November',
        12 => 'December',
    ];

    public static function getNumericMonth($number = 0)
    {
        return array_search($number,self::$MONTHS);
    }

    public static function getStringMonth($string = '')
    {
        return self::$MONTHS[$string];
    }

    public static function getMonths()
    {
        return self::$MONTHS;
    }

}
