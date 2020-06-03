<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (! User::where('name', 'user')->first()) {
            $user = User::create([
                'name' => 'admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('admin'),
                'email_verified_at' => Carbon::now(),
            ]);

            $user->roles()->attach(Role::where('name', 'admin')->first()->id);
        }
    }
}
