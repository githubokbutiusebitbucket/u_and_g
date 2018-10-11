<?php

use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'first_name' => 'Mark',
            'last_name' => 'Watney',
            'email' => 'admin@example.com',
            'password' => Hash::make('secret'),
        ]);

        factory(User::class, 10)->create();
    }
}
