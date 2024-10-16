<?php

namespace App\Domain\Services;

class ATMService
{
    public function calculateNotes(int $amount): array
    {
        $notes = [
            200 => 0,
            100 => 0,
            50 => 0,
            20 => 0,
            10 => 0,
            5 => 0,
            1 => 0,
        ];
        foreach ($notes as $note => $count) {
            if ($amount >= $note) {
                $notes[$note] = intdiv($amount, $note);
                $amount = $amount % $note;
            }
        }
        return $notes;
    }
}
