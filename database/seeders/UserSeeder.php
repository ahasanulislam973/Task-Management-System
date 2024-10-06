<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        User::create([
            "name"              => "Md. Ahasanul Islam",
            "email"             => "ahasanulislam973@gmail.com",
            "role"              => "user",
            "password"          => bcrypt("ahasanulislam973@gmail.com"),        
        ]);
    }
}
