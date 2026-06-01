<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run()
    {
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $auctioneer = Role::firstOrCreate(['name' => 'subastador']);
        $bidder = Role::firstOrCreate(['name' => 'ofertante']);
        $user = Role::firstOrCreate(['name' => 'user']);

        $manageUsers = Permission::firstOrCreate(['name' => 'manage users']);
        $viewReports = Permission::firstOrCreate(['name' => 'view reports']);

        $admin->givePermissionTo([$manageUsers, $viewReports]);
        $auctioneer->givePermissionTo([$viewReports]);
        $bidder->givePermissionTo([$viewReports]);
        $user->givePermissionTo([$viewReports]);
    }
}
