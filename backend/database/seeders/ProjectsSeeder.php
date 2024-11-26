<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ProjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('projects')->insert([
            [
                'cate_id' => 1,
                'collected_amount' => 15000,    
                'create_by' => 1,
                'description' => 'Xây dựng các trường học tại khu vực nông thôn',
                'end_date' => '2024-06-01',
                'goal_amount' => 50000,
                'name' => 'Xây trường học',     
                'start_date' => '2024-01-01',
                'status' => 'Active',
                
                
                
            ],
            [
                'cate_id' => 2,
                'collected_amount' => 3000,    
                'create_by' => 2,
                'description' => 'Tổ chức khám sức khỏe miễn phí',
                'end_date' => '2024-10-01',
                'goal_amount' => 30000,
                'name' => 'Chương trình y tế',     
                'start_date' => '2024-01-05',
                'status' => 'Active',
            ],
            [
                'cate_id' => 3,
                'collected_amount' => 10000,    
                'create_by' => 1,
                'description' => 'Làm sạch các con sông bị ô nhiễm',
                'end_date' => '2024-05-07',
                'goal_amount' => 20000,
                'name' => 'Dọn sạch sông',     
                'start_date' => '2024-05-02',
                'status' => 'Active',
            ],
            [
                'cate_id' => 4,
                'collected_amount' => 4000,    
                'create_by' => 2,
                'description' => 'Thiết lập các thư viện phục vụ cộng đồng',
                'end_date' => '2024-04-05',
                'goal_amount' => 40000,
                'name' => 'Thư viện cộng đồng',     
                'start_date' => '2024-04-01',
                'status' => 'Active',
            ],
            // Add more projects as needed
        ]);
    }
}