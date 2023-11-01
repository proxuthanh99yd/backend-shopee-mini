<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = new User();
        $user->name = "admin";
        $user->email = "admin@gmail.com";
        $user->password = Hash::make("admin");
        $user->address = "Viet Nam";
        $user->phone = "0123456789";
        $user->gender = 2;
        $user->status = 1;
        $user->role = 0;
        $user->save();
    }
}
