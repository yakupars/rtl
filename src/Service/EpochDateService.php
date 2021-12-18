<?php

namespace Rtl\Service;

use stdClass;

final class EpochDateService
{
    /**
     * @param int $weekDay
     * @param int $day
     * @param int $month
     * @param int $year
     *
     * @return stdClass
     */
    public function getNextGivenWeekDayDateFromDayMonthYear(int $weekDay, int $day, int $month, int $year): stdClass
    {
        $daysSinceEpoch = $this->getDaysSinceEpoch($day, $month, $year);
        $weekDayOfDate = $this->getWeekDayFromDayMonthYear($day, $month, $year);
        $diff = $this->getWeekDayDifference($weekDayOfDate, $weekDay);

        $daysSinceEpoch += $diff === 0 ? 7 : $diff;

        return $this->getDateFromEpochDays($daysSinceEpoch);
    }

    /**
     * @param int $day
     * @param int $month
     * @param int $year
     *
     * @return int
     */
    private function getDaysSinceEpoch(int $day, int $month, int $year): int
    {
        $year -= $month <= 2 ? 1 : 0;

        $era = intdiv(($year >= 0 ? $year : $year - 399), 400);
        $yoe = $year - $era * 400;
        $doy = intdiv((153 * ($month > 2 ? $month - 3 : $month + 9) + 2), 5) + $day - 1;
        $doe = $yoe * 365 + intdiv($yoe, 4) - intdiv($yoe, 100) + $doy;

        return ($era * 146097) + $doe - 719468;
    }

    /**
     * @param int $day
     * @param int $month
     * @param int $year
     *
     * @return int
     */
    public function getWeekDayFromDayMonthYear(int $day, int $month, int $year): int
    {
        return $this->getWeekDayFromDays($this->getDaysSinceEpoch($day, $month, $year));
    }

    /**
     * @param int $daysSinceEpoch
     *
     * @return int
     */
    private function getWeekDayFromDays(int $daysSinceEpoch): int
    {
        return $daysSinceEpoch >= -4 ? ($daysSinceEpoch + 4) % 7 : ($daysSinceEpoch + 5) % 7 + 6;
    }

    /**
     * @param int $from
     * @param int $to
     *
     * @return int
     */
    private function getWeekDayDifference(int $from, int $to): int
    {
        $to -= $from;

        return $to < 0 ? $to + 7 : $to;
    }

    /**
     * @param int $daysSinceEpoch
     *
     * @return stdClass
     */
    private function getDateFromEpochDays(int $daysSinceEpoch): stdClass
    {
        $daysSinceEpoch += 719468;
        $era = intdiv(($daysSinceEpoch >= 0 ? $daysSinceEpoch : $daysSinceEpoch - 146096), 146097);
        $doe = $daysSinceEpoch - $era * 146097;
        $yoe = intdiv(($doe - intdiv($doe, 1460) + intdiv($doe, 36524) - intdiv($doe, 146096)), 365);
        $y = $yoe + $era * 400;
        $doy = $doe - (365 * $yoe + intdiv($yoe, 4) - intdiv($yoe, 100));
        $mp = intdiv((5 * $doy + 2), 153);
        $d = $doy - intdiv((153 * $mp + 2), 5) + 1;
        $m = $mp < 10 ? $mp + 3 : $mp - 9;
        $y += $m <= 2 ? 1 : 0;

        $date = new stdClass();
        $date->year = (int)$y;
        $date->month = $m;
        $date->day = (int)$d;

        return $date;
    }

    /**
     * @param int $weekDay
     * @param int $month
     * @param int $year
     *
     * @return int
     */
    public function getDayOfLastGivenWeekDayFromMonthYear(int $weekDay, int $month, int $year): int
    {
        $last = $this->getLastDayOfMonthYear($month, $year);
        $weekDayLast = $this->getWeekDayFromDays($this->getDaysSinceEpoch($last, $month, $year));

        return $last - $this->getWeekDayDifference($weekDay, $weekDayLast);
    }

    /**
     * @param int $month
     * @param int $year
     *
     * @return int
     */
    public function getLastDayOfMonthYear(int $month, int $year): int
    {
        return $month != 2 || !$this->isLeap($year) ? $this->getLastDayOfMonthCommonYear($month) : 29;
    }

    /**
     * @param int $year
     *
     * @return bool
     */
    private function isLeap(int $year): bool
    {
        return $year % 4 == 0 && ($year % 100 != 0 || $year % 400 == 0);
    }

    /**
     * @param int $month
     *
     * @return int
     */
    private function getLastDayOfMonthCommonYear(int $month): int
    {
        $monthDays = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

        return $monthDays[$month - 1];
    }

    /**
     * @param int $month
     * @param int $year
     *
     * @return stdClass
     */
    public function getNextMonthDateFromMonthYear(int $month, int $year): stdClass
    {
        $last = $this->getLastDayOfMonthYear($month, $year);
        $days = $this->getDaysSinceEpoch($last, $month, $year);
        $days++;

        return $this->getDateFromEpochDays($days);
    }

    /**
     * @param int $n
     * @param int $weekDay
     * @param int $month
     * @param int $year
     *
     * @return int
     */
    private function getNthWeekDayOfMonthYear(int $n, int $weekDay, int $month, int $year): int
    {
        $weekDay1st = $this->getWeekDayFromDayMonthYear(1, $month, $year);

        return $this->getWeekDayDifference($weekDay1st, $weekDay) + 1 + ($n - 1) * 7;
    }

    /**
     * @param int $month
     *
     * @return int
     */
    private function getLastDayOfMonthLeapYear(int $month): int
    {
        $monthDays = [31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

        return $monthDays[$month - 1];
    }

    /**
     * @param int $weekDay
     *
     * @return int
     */
    private function getNextWeekday(int $weekDay): int
    {
        return $weekDay < 6 ? $weekDay + 1 : 0;
    }

    /**
     * @param int $weekDay
     *
     * @return int
     */
    private function getPrevWeekday(int $weekDay): int
    {
        return $weekDay > 0 ? $weekDay - 1 : 6;
    }
}