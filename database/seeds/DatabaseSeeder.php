<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserTableSeeder::class);
        $this->call(TaskTableSeeder::class);
        $this->call(TagTableSeeder::class);
        $this->call(TaskTagTableSeeder::class);
    }
}
