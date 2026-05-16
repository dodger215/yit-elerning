<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function up(): void
    {
        $rbac = Config::get('rbac');

        // Create Permissions
        foreach ($rbac['permissions'] as $resource => $actions) {
            foreach ($actions as $action) {
                Permission::firstOrCreate([
                    'resource' => $resource,
                    'action' => $action,
                ]);
            }
        }

        // Create Roles and Assign Permissions
        foreach ($rbac['roles'] as $roleKey => $roleData) {
            $role = Role::firstOrCreate(
                ['name' => $roleKey],
                ['description' => $roleData['description']]
            );

            $permissionIds = [];
            foreach ($roleData['permissions'] as $permPattern) {
                if (str_contains($permPattern, '.*')) {
                    $resource = str_replace('.*', '', $permPattern);
                    $ids = Permission::where('resource', $resource)->pluck('id')->toArray();
                    $permissionIds = array_merge($permissionIds, $ids);
                } else {
                    [$resource, $action] = explode('.', $permPattern);
                    $permission = Permission::where('resource', $resource)
                        ->where('action', $action)
                        ->first();
                    if ($permission) {
                        $permissionIds[] = $permission->id;
                    }
                }
            }

            $role->permissions()->sync(array_unique($permissionIds));
        }
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->up();
    }
}
