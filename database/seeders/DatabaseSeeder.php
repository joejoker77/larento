<?php

namespace Database\Seeders;

use App\Entities\User\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'HaliGali',
            'email' => 'joejoker77@gmail.com',
            'password' => Hash::make('1Kv?2InH!3yVnZ!')
        ]);
    }
}
