<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LoanSubmissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i <= 5; $i++) {
            DB::table('loan_submissions')->insert([
                'user_id' => mt_rand(5, 10),
                'total_loan' => mt_rand(1000000, 5000000),
                'start_date' => Carbon::createFromDate(date('Y'), mt_rand(1, 12), mt_rand(1, 31)),
                'is_approve' => 0,
                'message' => 'Halo ingin meminjam ' . $i
            ]);
        }
    }
}
