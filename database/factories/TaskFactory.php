<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Task;
use App\User;
use Faker\Generator as Faker;

$factory->define(Task::class, function (Faker $faker) {
    return [
        'body'       => $faker->paragraphs(rand(1, 2), true),
        'created_by' => User::all()->random()->id,
        'updated_by' => User::all()->random()->id,
    ];
});
