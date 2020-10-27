<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LoansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('loans')->insert([
            'user_id' => 1,
            'employee_id' => mt_rand(1, 28),
            'start_date' => Carbon::createFromDate(date('Y'), mt_rand(1, 6), mt_rand(1, date('d'))),
            'due_date' => Carbon::createFromDate(date('Y'), mt_rand(6, 12), mt_rand(1, date('d'))),
            'loan_interest' => 10,
            'total_loan' => 1000000,
            'total_loan_with_interest' =>  1100000,
            'total_payment' => 500000,
            'total_payment_interest' => 50000,
            'total_payment_with_interest' => 550000,
            'payment_counts' => 2,
            'status' => 0,
            'is_approve' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('loans')->insert([
            'user_id' => 2,
            'employee_id' => mt_rand(1, 28),
            'start_date' => Carbon::createFromDate(date('Y'), mt_rand(1, 6), mt_rand(1, date('d'))),
            'due_date' => Carbon::createFromDate(date('Y'), mt_rand(6, 12), mt_rand(1, date('d'))),
            'loan_interest' => 10,
            'total_loan' => 5000000,
            'total_loan_with_interest' =>  5500000,
            'total_payment' => 10000000,
            'total_payment_interest' => 1000000,
            'total_payment_with_interest' => 11000000,
            'payment_counts' => 5,
            'status' => 1,
            'is_approve' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('loans')->insert([
            'user_id' => 3,
            'employee_id' => mt_rand(1, 28),
            'start_date' => Carbon::createFromDate(date('Y'), mt_rand(1, 6), mt_rand(1, date('d'))),
            'due_date' => Carbon::createFromDate(date('Y'), mt_rand(6, 12), mt_rand(1, date('d'))),
            'loan_interest' => 10,
            'total_loan' => 20000000,
            'total_loan_with_interest' =>  22000000,
            'total_payment' => 5000000,
            'total_payment_interest' => 500000,
            'total_payment_with_interest' => 5500000,
            'payment_counts' => 4,
            'status' => 2,
            'is_approve' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        for ($i = 0; $i <= 100; $i++) {
            $loan_interest = mt_rand(1, 10);
            $total_loan = mt_rand(1000000, 50000000);

            $total_payment = mt_rand(1000000, 5000000);
            $total_payment_interest = mt_rand(1000000, 3000000);

            DB::table('loans')->insert([
                'user_id' => mt_rand(5, 30),
                'employee_id' => 1,
                'start_date' => Carbon::createFromDate(date('Y'), mt_rand(1, 6), mt_rand(1, date('d'))),
                'due_date' => Carbon::createFromDate(date('Y'), mt_rand(6, 12), mt_rand(1, date('d'))),
                'loan_interest' => $loan_interest,
                'total_loan' => $total_loan,
                'total_loan_with_interest' =>  $total_loan * $loan_interest,
                'total_payment' => $total_payment,
                'total_payment_interest' => $total_payment_interest,
                'total_payment_with_interest' => $total_payment + $total_payment_interest,
                'payment_counts' => mt_rand(1, 12),
                'status' => 2,
                'is_approve' => 1,
                'created_at' => Carbon::createFromDate(date('Y'), mt_rand(1, 12), mt_rand(1, date('d'))),
                'updated_at' => now()
            ]);
        }
    }
}
