<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class NotificationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('notifications')->insert([
            ['content' => 'Welcome to the platform!', 'user_id' => 2],
            ['content' => 'Project update available.', 'user_id' => 2],
            ['content' => 'Payment received.', 'user_id' => 2],
            ['content' => 'New project created.',  'user_id' => 2],
            ['content' => 'Your project has been approved.','user_id' => 2],
        ]);
    }
}