<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class UsersProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('users_project')->insert([
            ['project_id' => 1, 'user_id' => 1],
            ['project_id' => 2, 'user_id' => 2],
            ['project_id' => 3, 'user_id' => 1],
            ['project_id' => 4, 'user_id' => 2],
        ]);
    }
}