<?php

use App\Group;
use App\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        app(UserSeeder::class)->run();
        app(GroupSeeder::class)->run();

        User::all()->each(function ($user) {
            $user->groups()
                ->saveMany(Group::inRandomOrder()->take(rand(1,3))->get());
        });
    }
}
