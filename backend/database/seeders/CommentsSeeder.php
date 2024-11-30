<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class CommentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('comments')->insert([
            ['content' => 'Great initiative!', 'parent_comment_id' => null, 'project_id' => 1, 'user_id' => 1],
            ['content' => 'How can I contribute?', 'parent_comment_id' => null,'project_id' => 2, 'user_id' => 2],
            // Add more comments as needed
        ]);
    }
}