<?php

use App\Tag;
use App\Task;
use App\TaskTag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class TaskTagTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run():void
    {
        // Truncate existing records
        Schema::disableForeignKeyConstraints(); // Cross compatible; lesser of two evils
        TaskTag::truncate();
        Schema::enableForeignKeyConstraints();

        $tags = Tag::all();

        Task::all()->each(function ($task) use ($tags) {
            $task->tags()->attach(
                $tags->random(rand(0, 4))->pluck('id')->toArray()
            );
        });
    }
}
