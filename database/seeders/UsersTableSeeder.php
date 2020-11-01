<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Administrator',
            'gender' => mt_rand(0, 1),
            'email' => 'admin@mail.com',
            'phone_number' => '081346153182',
            'join_date' => Carbon::createFromDate(mt_rand(1950, 2020), mt_rand(1, 12), mt_rand(1, 31)),
            'date_of_birth' => Carbon::createFromDate(mt_rand(1950, 2020), mt_rand(1, 12), mt_rand(1, 31)),
            'email_verified_at' => now(),
            'password' => Hash::make('secret'),
            'address' => 'Jalan Rapak Indah',
            'job' => 'Pegawai Swasta',
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('users')->insert([
            'name' => 'Pegawai',
            'gender' => mt_rand(0, 1),
            'email' => 'pegawai@mail.com',
            'phone_number' => '0813564712841',
            'join_date' => Carbon::createFromDate(mt_rand(1950, 2020), mt_rand(1, 12), mt_rand(1, 31)),
            'date_of_birth' => Carbon::createFromDate(mt_rand(1950, 2020), mt_rand(1, 12), mt_rand(1, 31)),
            'email_verified_at' => now(),
            'password' => Hash::make('secret'),
            'address' => 'Jalan Rapak Suka',
            'job' => 'Pegawai Swasta',
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('users')->insert([
            'name' => 'Pengguna',
            'gender' => mt_rand(0, 1),
            'email' => 'pengguna@mail.com',
            'phone_number' => '081346153182',
            'join_date' => Carbon::createFromDate(mt_rand(1950, 2020), mt_rand(1, 12), mt_rand(1, 31)),
            'date_of_birth' => Carbon::createFromDate(mt_rand(1950, 2020), mt_rand(1, 12), mt_rand(1, 31)),
            'email_verified_at' => now(),
            'password' => Hash::make('secret'),
            'address' => 'Jalan Rapak Pinggir',
            'job' => 'Pegawai Swasta',
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        User::factory()->count(29)->create();
    }
}
