<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create the primary user "Ked"
        $user = User::firstOrCreate(
            ['email' => 'kelvinenosdzah2@gmail.com'],
            [
                'username' => 'Ked',
                'first_name' => 'Ked',
                'last_name' => 'Enos',
                'is_active' => true,
            ]
        );

        // Assign Roles: Supervisor, Instructor, and Editor
        $roles = Role::whereIn('name', ['supervisor', 'instructor', 'editor'])->get();
        $user->roles()->sync($roles->pluck('id'));

        $this->command->info("User 'Ked' created and assigned roles: supervisor, instructor, editor.");
    }
}
