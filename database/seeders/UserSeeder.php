<?php

namespace Database\Seeders;
use App\Models\User;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'memberType' => 'Admin',
            'firstName' => 'Clark',
            'lastName' => 'He',
            'email' => 'skla2003@hotmail.com',
            'password' => '123456',

        ]);

        // Create 50 additional fake users with 'Member' role
        User::factory()->count(50)->create();
    }
}
