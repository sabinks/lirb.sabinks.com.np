<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!User::whereEmail('superadmin@mail.com')->first()) {
            $user = User::create([
                'name' => 'Superadmin',
                'email' => 'superadmin@mail.com',
                'password' => Hash::make('pass1234'),
                'email_verified_at' => Carbon::now(),
            ]);
            $role = Role::whereName('Superadmin')->first();
            $user->assignRole($role);
        }
        if (!User::whereEmail('member@mail.com')->first()) {
            $user = User::create([
                'name' => 'Member',
                'email' => 'member@mail.com',
                'password' => Hash::make('pass1234'),
                'email_verified_at' => Carbon::now(),
            ]);
            $role = Role::whereName('Member')->first();
            $user->assignRole($role);
        }
    }
}
