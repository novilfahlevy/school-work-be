<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([UsersTableSeeder::class]);
        $this->call([LoansTableSeeder::class]);
        $this->call([PaymentsTableSeeder::class]);
        $this->call([DepositsTableSeeder::class]);
        $this->call([RolesTableSeeder::class]);
        $this->call([UserHasRolesTableSeeder::class]);
        $this->call([BalancesTableSeeder::class]);
    }
}
