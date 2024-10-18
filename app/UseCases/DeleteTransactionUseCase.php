<?php

namespace App\UseCases;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class DeleteTransactionUseCase
{
    public function execute(int $transactionId): bool
    {

        if (!Gate::allows('delete-transactions')) {
           return false;
        }

        $transaction = Transaction::find($transactionId);
        if (!$transaction) {
            return false;
        }
        $transaction->delete();
        return true;
    }
}
