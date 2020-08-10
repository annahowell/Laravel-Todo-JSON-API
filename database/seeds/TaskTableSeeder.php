<?php

use App\Task;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class TaskTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Truncate existing records
        Schema::disableForeignKeyConstraints(); // Cross compatible; lesser of two evils
        Task::truncate();
        Schema::enableForeignKeyConstraints();

        factory(Task::class, 10)->create();
    }
}
