<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create 10 fake users
        $users = User::factory(10)->create();

        // Create 100 fake products and spread them across those 10 users
        Product::factory(100)
            ->recycle($users)
            ->create();
    }
}
