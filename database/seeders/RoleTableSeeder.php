<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (! Role::where('name', 'member')->first()) {
            Role::create([
                'name' => 'member',
                'label' => 'Member',
            ]);
            Role::create([
                'name' => 'admin',
                'label' => 'Admin',
            ]);
        }
    }
}
