<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('categories')->insert([
            ['name' => 'Giáo dục', 'description' => 'Các dự án liên quan đến giáo dục'],
            ['name' => 'Sức khỏe', 'description' => 'Các dự án về sức khỏe và chăm sóc y tế'],
            ['name' => 'Môi trường', 'description' => 'Các dự án bảo vệ môi trường và phát triển bền vững'],
            ['name' => 'Cộng đồng', 'description' => 'Các dự án phát triển cộng đồng'],
            ['name' => 'Công nghệ', 'description' => 'Các dự án về công nghệ và đổi mới sáng tạo'],
        ]);
    }
}