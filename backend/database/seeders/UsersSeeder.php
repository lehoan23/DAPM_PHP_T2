<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('users')->insert([
            [
            "address"=>"Đống Đa",
            "email"=>"admin@gmail.com",        
            "password"=>Hash::make("123456789"),
            "phone_number"=>"0899902632",      
            "role_id"=>"1",
            "username"=>"Admin",



            ],
            [
                "address"=>"Đống Đa",

                "email"=>"hoanggumayosi@gmail.com",
                "password"=>Hash::make("123456789"),
                "phone_number"=>"0899902632",

                "role_id"=>"2",
                "username"=>"Hoang",
            ],
            [
                "address"=>"Đống Đa",

                "email"=>"tung@gmail.com",
                "password"=>Hash::make("123456789"),
                "phone_number"=>"0899902632",

                "role_id"=>"3",
                "username"=>"Mai Tung",
            ]
    ]);


    }
}