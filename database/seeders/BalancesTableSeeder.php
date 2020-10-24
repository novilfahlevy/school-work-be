<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BalancesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('balances')->insert([
            'balance' => 300000000,
            'balanceable_type' => 'App\Models\Loan',
            'balanceable_id' => 1,
            'user_id' => 1,
            'type' => 1,
            'changed_at' => date('Y-m-d H:i:s')
        ]);
    }
}
