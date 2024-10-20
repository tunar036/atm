<?php

namespace App\UseCases;

use App\Models\Transaction;
use Exception;

class DeleteTransactionUseCase
{
    public function execute($transactionId): bool
    {
        $transactionId = intval($transactionId);
        $transaction = Transaction::find($transactionId);
        if (!$transaction) {
            throw new Exception('tranzaksiya tapilmadi');
        }

        $transaction->delete();
        return true;
    }
}
