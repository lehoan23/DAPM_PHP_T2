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
            ['project_id' => 9, 'user_id' => 1],
            ['project_id' => 10, 'user_id' => 2],
            ['project_id' => 11, 'user_id' => 1],
            ['project_id' => 12, 'user_id' => 2],
        ]);
    }
}