<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run():void
    {
        // Truncate existing records
        Schema::disableForeignKeyConstraints(); // Cross compatible; lesser of two evils
        User::truncate();
        Schema::enableForeignKeyConstraints();

        factory(User::class)->create([
            'displayname' => 'baz',
            'email'       => 'foo@bar.com',
        ]);
    }
}
