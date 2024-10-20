<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Transaction::create([
            'account_id' =>1,
            'amount' => 100.00,
            'type' => 'withdraw',
        ]);
        Transaction::create([
            'account_id' =>1,
            'amount' => 200.00,
            'type' => 'deposit',
        ]);
    }
}
