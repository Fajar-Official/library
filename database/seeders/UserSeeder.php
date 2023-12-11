<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Membuat role "petugas" jika belum ada
        $role = Role::firstOrCreate(['name' => 'petugas']);

        // Membuat izin "index transaction" jika belum ada
        $permission = Permission::firstOrCreate(['name' => 'index transaction']);

        // Menetapkan izin ke peran dan sebaliknya
        $role->givePermissionTo($permission);

        // Membuat user admin
        $user = User::create([
            'name' => 'root',
            'email' => 'root@example.com',
            'password' => Hash::make('password'),
        ]);

        // Menambahkan role "petugas" ke user admin
        $user->assignRole($role);

        // Menampilkan informasi pengguna dengan peran
        $userWithRoles = User::with('roles')->get();

        return $userWithRoles;
    }
}
