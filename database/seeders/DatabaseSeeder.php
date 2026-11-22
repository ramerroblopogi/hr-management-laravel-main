<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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

        // A manager account used to test the manager abilities
        User::create([
            'email' => 'manager@manager.com',
            'password' => bcrypt('password'),
            'name' => 'The Manager',
            'is_manager' => true,
        ]);
    }
}