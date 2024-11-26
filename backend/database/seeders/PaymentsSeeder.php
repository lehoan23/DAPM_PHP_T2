<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class PaymentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('payments')->insert([
            ['amount' => 5000, 'payment_date' => '2024-01-01', 'payment_method' => 'Thẻ tín dụng','project_id' => 9, 'status' => 'Success', 'user_id' => 5],
            ['amount' => 3000, 'payment_date' => '2024-01-01', 'payment_method' => 'Momo', 'project_id' => 10, 'status' => 'Pending', 'user_id' => 5],
            ['amount' => 2000, 'payment_date' => '2024-01-01', 'payment_method' => 'Chuyển khoản ngân hàng','project_id' => 11, 'status' => 'Failed', 'user_id' => 5],
            ['amount' => 4000, 'payment_date' => '2024-01-01', 'payment_method' => 'Thẻ tín dụng', 'project_id' => 12, 'status' => 'Success', 'user_id' => 2],
            ['amount' => 10000, 'payment_date' => '2024-11-24', 'payment_method' => 'Momo', 'project_id' => 9, 'status' => 'Success', 'user_id' => 5],
        ]);
    }
}