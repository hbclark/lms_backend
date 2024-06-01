<?php

namespace Database\Seeders;
use App\Models\UserBook;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserBookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserBook::factory()->count(10)->create();
    }
}
