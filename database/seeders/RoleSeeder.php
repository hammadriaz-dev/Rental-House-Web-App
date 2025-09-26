<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' =>  'landlord']);
        Role::create(['name' => 'tenant']);

        $user = User::create([
            'name'  => 'admin',
            'email' => 'admin@gmail.com',
            'CNIC'  => '3120202020202',
            'password' => Hash::make('password'),
        ]);

        $user->assignRole('admin');
    }
}
