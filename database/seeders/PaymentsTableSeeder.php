<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('payments')->insert([
            'loan_id' => 1,
            'employee_id' => mt_rand(5, 10),
            'due_date' => Carbon::createFromDate(date('Y'), mt_rand(1, 6), mt_rand(1, date('d'))),
            'payment_date' => Carbon::createFromDate(date('Y'), mt_rand(6, 12), mt_rand(1, date('d'))),
            'payment_number' => 1,
            'status' => 1,
            'description' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('payments')->insert([
            'loan_id' => 1,
            'employee_id' => mt_rand(5, 10),
            'due_date' => Carbon::createFromDate(date('Y'), mt_rand(1, 6), mt_rand(1, date('d'))),
            'payment_date' => Carbon::createFromDate(date('Y'), mt_rand(6, 12), mt_rand(1, date('d'))),
            'payment_number' => 2,
            'status' => 1,
            'description' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('payments')->insert([
            'loan_id' => 1,
            'employee_id' => mt_rand(5, 10),
            'due_date' => Carbon::createFromDate(date('Y'), mt_rand(1, 6), mt_rand(1, date('d'))),
            'payment_date' => Carbon::createFromDate(date('Y'), mt_rand(6, 12), mt_rand(1, date('d'))),
            'payment_number' => 3,
            'status' => 1,
            'description' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('payments')->insert([
            'loan_id' => 1,
            'employee_id' => null,
            'due_date' => Carbon::createFromDate(date('Y'), mt_rand(1, 6), mt_rand(1, date('d'))),
            'payment_date' => null,
            'payment_number' => 4,
            'status' => 0,
            'description' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('payments')->insert([
            'loan_id' => 1,
            'employee_id' => null,
            'due_date' => Carbon::createFromDate(date('Y'), mt_rand(1, 6), mt_rand(1, date('d'))),
            'payment_date' => null,
            'payment_number' => 5,
            'status' => 0,
            'description' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // 

        DB::table('payments')->insert([
            'loan_id' => 2,
            'employee_id' => mt_rand(5, 10),
            'due_date' => Carbon::createFromDate(date('Y'), mt_rand(1, 6), mt_rand(1, date('d'))),
            'payment_date' => Carbon::createFromDate(date('Y'), mt_rand(6, 12), mt_rand(1, date('d'))),
            'payment_number' => 1,
            'status' => 1,
            'description' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('payments')->insert([
            'loan_id' => 2,
            'employee_id' => mt_rand(5, 10),
            'due_date' => Carbon::createFromDate(date('Y'), mt_rand(1, 6), mt_rand(1, date('d'))),
            'payment_date' => Carbon::createFromDate(date('Y'), mt_rand(6, 12), mt_rand(1, date('d'))),
            'payment_number' => 2,
            'status' => 1,
            'description' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('payments')->insert([
            'loan_id' => 2,
            'employee_id' => null,
            'due_date' => Carbon::createFromDate(date('Y'), mt_rand(1, 6), mt_rand(1, date('d'))),
            'payment_date' => null,
            'payment_number' => 3,
            'status' => 0,
            'description' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('payments')->insert([
            'loan_id' => 2,
            'employee_id' => null,
            'due_date' => Carbon::createFromDate(date('Y'), mt_rand(1, 6), mt_rand(1, date('d'))),
            'payment_date' => null,
            'payment_number' => 4,
            'status' => 0,
            'description' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('payments')->insert([
            'loan_id' => 2,
            'employee_id' => mt_rand(5, 10),
            'due_date' => Carbon::createFromDate(date('Y'), mt_rand(1, 6), mt_rand(1, date('d'))),
            'payment_date' => Carbon::createFromDate(date('Y'), mt_rand(6, 12), mt_rand(1, date('d'))),
            'payment_number' => 5,
            'status' => 1,
            'description' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // 

        DB::table('payments')->insert([
            'loan_id' => 3,
            'employee_id' => null,
            'due_date' => Carbon::createFromDate(date('Y'), mt_rand(1, 6), mt_rand(1, date('d'))),
            'payment_date' => null,
            'payment_number' => 1,
            'status' => 0,
            'description' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('payments')->insert([
            'loan_id' => 3,
            'employee_id' => mt_rand(5, 10),
            'due_date' => Carbon::createFromDate(date('Y'), mt_rand(1, 6), mt_rand(1, date('d'))),
            'payment_date' => Carbon::createFromDate(date('Y'), mt_rand(6, 12), mt_rand(1, date('d'))),
            'payment_number' => 2,
            'status' => 1,
            'description' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('payments')->insert([
            'loan_id' => 3,
            'employee_id' => null,
            'due_date' => Carbon::createFromDate(date('Y'), mt_rand(1, 6), mt_rand(1, date('d'))),
            'payment_date' => null,
            'payment_number' => 3,
            'status' => 0,
            'description' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('payments')->insert([
            'loan_id' => 3,
            'employee_id' => mt_rand(5, 10),
            'due_date' => Carbon::createFromDate(date('Y'), mt_rand(1, 6), mt_rand(1, date('d'))),
            'payment_date' => Carbon::createFromDate(date('Y'), mt_rand(6, 12), mt_rand(1, date('d'))),
            'payment_number' => 4,
            'status' => 1,
            'description' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('payments')->insert([
            'loan_id' => 3,
            'employee_id' => mt_rand(5, 10),
            'due_date' => Carbon::createFromDate(date('Y'), mt_rand(1, 6), mt_rand(1, date('d'))),
            'payment_date' => Carbon::createFromDate(date('Y'), mt_rand(6, 12), mt_rand(1, date('d'))),
            'payment_number' => 5,
            'status' => 1,
            'description' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
