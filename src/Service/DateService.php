<?php

namespace Rtl\Service;

use Rtl\Data\WeekDay;
use Rtl\Dto\Date;

class DateService
{
    /**
     * @param EpochDateService $epochDateService
     */
    public function __construct(private EpochDateService $epochDateService)
    {
    }

    /**
     * @return Date
     */
    public function getTodayAsDate(): Date
    {
        exec("date '+%d.%m.%Y'", $output);
        return Date::fromdmY(array_shift($output));
    }

    /**
     * @param Date $date
     *
     * @return Date
     */
    public function getBonusDateFromDate(Date $date): Date
    {
        $nextMonthDateStd = $this->epochDateService->getNextMonthDateFromMonthYear($date->getMonth(), $date->getYear());
        $weekDay = $this->epochDateService->getWeekDayFromDayMonthYear(
            15,
            $nextMonthDateStd->month,
            $nextMonthDateStd->year
        );

        if (in_array($weekDay, WeekDay::WorkDays())) {
            return new Date(15, $nextMonthDateStd->month, $nextMonthDateStd->year);
        }

        $nextMonthValidDateStd = $this->epochDateService->getNextGivenWeekDayDateFromDayMonthYear(
            WeekDay::WEDNESDAY,
            15,
            $nextMonthDateStd->month,
            $nextMonthDateStd->year
        );

        return new Date($nextMonthValidDateStd->day, $nextMonthValidDateStd->month, $nextMonthValidDateStd->year);
    }

    /**
     * @param Date $date
     *
     * @return Date
     */
    public function getLastWorkDateFromDate(Date $date): Date
    {
        $lastDayOfMonth = $this->epochDateService->getLastDayOfMonthYear($date->getMonth(), $date->getYear());

        $weekDay = $this->epochDateService->getWeekDayFromDayMonthYear(
            $lastDayOfMonth,
            $date->getMonth(),
            $date->getYear()
        );

        if (in_array($weekDay, WeekDay::WorkDays())) {
            return new Date($lastDayOfMonth, $date->getMonth(), $date->getYear());
        }

        $lastFriday = $this->epochDateService->getDayOfLastGivenWeekDayFromMonthYear(
            WeekDay::FRIDAY,
            $date->getMonth(),
            $date->getYear()
        );

        return new Date($lastFriday, $date->getMonth(), $date->getYear());
    }
}