<?php

namespace Database\Seeders;

use App\Models\Banknote;
use Illuminate\Database\Seeder;

class BanknoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $denominations = [200,100,50,20,10,5,1];
        $quantities = [20,20,20,20,20,20,20];
        foreach($denominations as $index=>$denomination){
            Banknote::create([
                'denomination'=>$denomination,
                'quantity'=>$quantities[$index]
            ]);
        }
    }
}
