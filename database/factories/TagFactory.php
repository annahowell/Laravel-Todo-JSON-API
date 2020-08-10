<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Tag;
use App\User;
use Faker\Generator as Faker;

$factory->define(Tag::class, function (Faker $faker) {
    return [
        'title'   => $faker->unique()->word(),
        'created_by' => User::all()->random()->id,
        'color'   => $faker->unique()->hexcolor(),
    ];
});
