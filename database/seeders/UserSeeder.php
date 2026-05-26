<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create specific users and assign roles
        $roles = ['dev', 'admin', 'user'];

        foreach ($roles as $roleName) {
            $user = User::updateOrCreate(
                ['email' => "{$roleName}@resepku.com"],
                [
                    'name' => ucfirst($roleName) . ' User',
                    'username' => $roleName,
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'is_active' => '1',
                ]
            );

            $user->syncRoles([$roleName]);
        }

        // 2. Create 1000 users using factory with id_ID region
        $faker = fake('id_ID');

        $users = User::factory()->count(1000)->create([
            'name' => fn() => $faker->name(),
            'username' => fn() => $faker->unique()->userName(),
            'email' => fn() => $faker->unique()->safeEmail(),
            'telp' => fn() => $faker->phoneNumber(),
        ]);

        // 3. Assign 'user' role to all 1000 users in bulk for speed
        $userRole = Role::where('name', 'user')->first();

        if ($userRole) {
            $roleRelations = [];
            foreach ($users as $user) {
                $roleRelations[] = [
                    'role_id' => $userRole->id,
                    'model_type' => User::class,
                    'model_id' => $user->id,
                ];
            }

            foreach (array_chunk($roleRelations, 200) as $chunk) {
                DB::table('model_has_roles')->insert($chunk);
            }
        }

        // Clear Spatie Permission cache to reflect changes
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        //--------------- sekian user berhasil di buat
        $this->command->info('//--------------- sekian user berhasil di buat');
    }
}
