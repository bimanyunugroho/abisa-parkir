<?php

namespace App\Helpers;

use App\Models\Transaction;
use App\Models\ParkingArea;
use Carbon\Carbon;

class NoTicketHelper
{
    public static function generateNoTicket(ParkingArea $parkingArea)
    {
        $now = Carbon::now();
        $year = $now->format('y');
        $month = $now->format('n');
        $day = $now->format('j');

        $datePrefix = $year . $month . $day;

        $lastTransaction = Transaction::where('no_ticket', 'like', $datePrefix . '%')
            ->where('parking_area_id', $parkingArea->id)
            ->orderBy('no_ticket', 'desc')
            ->first();

        if ($lastTransaction) {
            $lastNumber = intval(substr($lastTransaction->no_ticket, 6, 6));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $numberPart = str_pad($newNumber, 6, '0', STR_PAD_LEFT);
        $areaPart = $parkingArea->name;

        return $datePrefix . $numberPart . '-' . $areaPart;
    }
}
