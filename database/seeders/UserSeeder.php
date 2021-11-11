<?php

namespace Database\Seeders;

use App\Models\role;
use App\Models\User;
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
        collect([
            [
                'name' => 'reza',
                'email' => 'rzhasibuan@gmail.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'ramadhani ',
                'email' => 'dani@gmail.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'ranggie viona zubainadah',
                'email' => 'ranggie@gmail.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        ])->each(function ($user) {
            User::create($user);
        });

        collect(['admin','moderator','editor'])->each(function ($role){
            Role::create(['name' => $role]);
        });

        User::find(1)->roles()->attach([1]);
        User::find(2)->roles()->attach([2]);
    }
}
