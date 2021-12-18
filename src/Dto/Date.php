<?php

namespace Rtl\Dto;

class Date
{
    /**
     * @param int $day
     * @param int $month
     * @param int $year
     */
    public function __construct(private int $day, private int $month, private int $year)
    {
    }

    /**
     * @param string $dmY
     * @param string $delimiter
     *
     * @return Date
     */
    public static function fromdmY(string $dmY, string $delimiter = "."): Date
    {
        return new self(...explode($delimiter, $dmY));
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return str_pad($this->getDay(), 2, 0, STR_PAD_LEFT) . "." . str_pad(
                $this->getMonth(),
                2,
                0,
                STR_PAD_LEFT
            ) . "." . $this->getYear();
    }

    /**
     * @return int
     */
    public function getDay(): int
    {
        return $this->day;
    }

    /**
     * @return int
     */
    public function getMonth(): int
    {
        return $this->month;
    }

    /**
     * @return int
     */
    public function getYear(): int
    {
        return $this->year;
    }
}