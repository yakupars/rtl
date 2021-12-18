<?php

namespace Rtl\Data;

/**
 * WeekDay helper class.
 */
abstract class WeekDay
{
    public const SUNDAY = 0;
    public const MONDAY = 1;
    public const TUESDAY = 2;
    public const WEDNESDAY = 3;
    public const THURSDAY = 4;
    public const FRIDAY = 5;
    public const SATURDAY = 6;

    /**
     * @return int[]
     */
    public static function WeekendDays(): array
    {
        return [
            self::SUNDAY,
            self::SATURDAY,
        ];
    }

    /**
     * @return int[]
     */
    public static function WorkDays(): array
    {
        return [
            self::MONDAY,
            self::TUESDAY,
            self::WEDNESDAY,
            self::THURSDAY,
            self::FRIDAY,
        ];
    }
}