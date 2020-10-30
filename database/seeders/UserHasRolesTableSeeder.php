<?php

namespace Database\Seeders;

use App\Models\UserHasRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserHasRolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_has_roles')->insert([
            'user_id' => 1,
            'role_id' => 1
        ]);

        DB::table('user_has_roles')->insert([
            'user_id' => 2,
            'role_id' => 2
        ]);

        DB::table('user_has_roles')->insert([
            'user_id' => 3,
            'role_id' => 3
        ]);

        UserHasRole::factory()->count(19)->create();
    }
}
