<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $obj = new Admin();
        $obj->name = 'Muhammad Abyan';
        $obj->email = 'admin@gmail.com';
        $obj->photo = 'admin.jpg';
        $obj->password = Hash::make('12');
        $obj->token = '';
        $obj->save();
    }
}
