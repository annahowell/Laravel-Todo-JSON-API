<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Task;
use App\Tag;
use App\TaskTag;

$factory->define(TaskTag::class, function () {
    return [
        'task_id' => Task::all()->random()->id,
        'tag_id'  =>  Tag::all()->random()->id,
    ];
});
