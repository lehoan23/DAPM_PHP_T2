<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('images')->insert([
            ['link' => 'https://i-vnexpress.vnecdn.net/2018/05/16/Cap-4-9550-1526460348.jpg', 'project_id' => 9],
            ['link' => 'https://bcp.cdnchinhphu.vn/Uploaded/dothanhhoai/2019_02_25/suc%20khoe.jpg', 'project_id' => 10],
            ['link' => 'https://static.kinhtedothi.vn/w640/images/upload//2023/05/31/anh1-dsdr-ns.png', 'project_id' => 11],
            ['link' => 'https://images2.thanhnien.vn/Uploads/2020/2020/4/10/cff34', 'project_id' => 12],
        ]);
    }
}