<?php

namespace Rtl\Data;

abstract class Month
{
    private static array $months = [
        1 => "January",
        2 => "February",
        3 => "March",
        4 => "April",
        5 => "May",
        6 => "June",
        7 => "July",
        8 => "August",
        9 => "September",
        10 => "October",
        11 => "November",
        12 => "December",
    ];

    /**
     * @param int $month
     *
     * @return string
     */
    public static function MonthName(int $month): string
    {
        return self::$months[$month];
    }
}