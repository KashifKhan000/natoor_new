<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::insert([
            'first_name' => 'Super',
            'last_name'  => 'Admin',
            'username'   => 'Super Admin',
            'email'      => 'SuperAdmin@natoorservices.com',
            'password'   => Hash::make('builtin2022'),
            'type'       => 0,
            'gender'     => 'Male',
            'status'     => 'Active',
            'mobile_number' => '+923000000000',
        ]);
    }
}
