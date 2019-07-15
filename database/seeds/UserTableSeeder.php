<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!User::where('name', 'user')->first()) {
            User::create([
                'name' => 'admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('admin'),
            ]);
        }
    }
}
