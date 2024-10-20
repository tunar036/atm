<?php

namespace App\UseCases;

use App\Models\Transaction;
use Exception;

class DeleteTransactionUseCase
{
    public function execute($transactionId): bool
    {
        if (gettype($transactionId) !== "integer") {
            throw new Exception('Tranzaksiya id-i integer olmalidir');
        }

        $transaction = Transaction::find($transactionId);
        if (!$transaction) {
            throw new Exception('tranzaksiya tapilmadi');
        }

        $transaction->delete();
        return true;
    }
}
