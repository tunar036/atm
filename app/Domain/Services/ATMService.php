<?php

namespace App\Domain\Services;

use App\Models\Banknote;
use Illuminate\Support\Facades\DB;

class ATMService
{
    public function calculateNotes($amount)
    {
        return DB::transaction(function() use ($amount) {
            $banknotes = Banknote::orderBy('denomination', 'desc')->get();
            $notesToDispense = [];
    
            foreach ($banknotes as $banknote) {
                if ($amount == 0) break;
    
                $denomination = $banknote->denomination;
                $quantity = $banknote->quantity;
    
                $requiredNotes = intdiv($amount, $denomination);
                $dispensedNotes = min($requiredNotes, $quantity);
    
                if ($dispensedNotes > 0) {
                    $notesToDispense[$denomination] = $dispensedNotes;
                    $amount -= $dispensedNotes * $denomination;
    
                    $banknote->quantity -= $dispensedNotes;
                    $banknote->save();
                }
            }
    
            if ($amount > 0) {
                throw new \Exception('Bankda yetərli miqdarda əskinas yoxdur.');
            }
    
            return $notesToDispense;
        });
    }
}
