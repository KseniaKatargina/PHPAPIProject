<?php

namespace App\Service;

use DateTime;

class DateService
{
    public function getTodayDate(): DateTime
    {
        return new DateTime('today');
    }
    public function generateDateRange(int $days): array
    {
        $today = new DateTime();
        $dates = array();

        for ($i = 0; $i < $days; $i++) {
            $date = $today->format('d-m-Y');
            $dates[] = $date;
            $today->modify('+1 day');
        }

        return $dates;
    }
}