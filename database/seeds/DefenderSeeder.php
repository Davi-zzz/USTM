<?php

use Illuminate\Database\Seeder;
use App\Permission;
use App\Role;
use App\User;

class DefenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createPermissions();
        $this->createRoles();
    }

    private function createPermissions()
    {
        // Seed the default permissions
        $permissions = Permission::defaultPermissions();

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }

        $this->command->info('Default Permissions added.');
    }

    private function createRoles()
    {
        $superadmin = Role::firstOrCreate([
            'name' => 'super_administrador',
            'description' => 'Super Administrador',
            'type_user' => 1
        ]);
        $superadmin->permissions()->sync(Permission::all());

        $this->command->info('Superadmin will have full rights');
    }
}
