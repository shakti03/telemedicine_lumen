<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User;
        $user->email = "admin@telemedicine.com";
        $user->first_name = "Admin";
        $user->last_name = "";
        $user->room_name = 'admin';
        $user->password = Hash::make('12345');
        $user->save();
    }
}
