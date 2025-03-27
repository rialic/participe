<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Repository\PermissionRepository;
use App\Repository\RoleRepository;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function __construct(
        private Role $role,
        private RoleRepository $roleRepository,
        private Permission $permission,
        private PermissionRepository $permissionRepository,
    )
    {}

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /* ADMIN */
        $isCreated = $this->hasRolePermission('ADMIN', 'ADMIN');
        $this->createRolePermission($isCreated);

        /* ADJUTOR */
        $isCreated = $this->hasRolePermission('ADJUTOR', 'MENU.DASHBOARD');
        $this->createRolePermission($isCreated);

        $isCreated = $this->hasRolePermission('ADJUTOR', 'MENU.EVENT');
        $this->createRolePermission($isCreated);

        $isCreated = $this->hasRolePermission('ADJUTOR', 'MENU.EVENT-WEBCLASS');
        $this->createRolePermission($isCreated);

        $isCreated = $this->hasRolePermission('ADJUTOR', 'MENU.EVENT-COURSE');
        $this->createRolePermission($isCreated);

        $isCreated = $this->hasRolePermission('ADJUTOR', 'EVENT.WEBCLASS-VIEW');
        $this->createRolePermission($isCreated);

        $isCreated = $this->hasRolePermission('ADJUTOR', 'EVENT.WEBCLASS-CREATE');
        $this->createRolePermission($isCreated);

        $isCreated = $this->hasRolePermission('ADJUTOR', 'EVENT.WEBCLASS-SAVE');
        $this->createRolePermission($isCreated);

        $isCreated = $this->hasRolePermission('ADJUTOR', 'EVENT.WEBCLASS-EDIT');
        $this->createRolePermission($isCreated);

        $isCreated = $this->hasRolePermission('ADJUTOR', 'EVENT.WEBCLASS-UPDATE');
        $this->createRolePermission($isCreated);

        $isCreated = $this->hasRolePermission('ADJUTOR', 'EVENT.WEBCLASS-DELETE');
        $this->createRolePermission($isCreated);

        $isCreated = $this->hasRolePermission('ADJUTOR', 'EVENT.WEBCLASS-CERTIFICATE');
        $this->createRolePermission($isCreated);

        $isCreated = $this->hasRolePermission('ADJUTOR', 'SETTINGS.USER-PROFILE');
        $this->createRolePermission($isCreated);

        /* USUÁRIO */
        $isCreated = $this->hasRolePermission('USUARIO', 'SETTINGS.USER-PROFILE');
        $this->createRolePermission($isCreated);
    }

    private function hasRolePermission($role, $permission)
    {
        $this->role = $this->roleRepository->getFirstData(['name' => $role]);
        $this->permission = $this->permissionRepository->getFirstData(['name' => $permission]);
        $count = $this->roleRepository->getModel()
                    ->whereHas('permissions', fn($query) => $query->where('permission_id', $this->permission->id))
                    ->where('id', $this->role->id)
                    ->count();

        return $count > 0;
    }

    private function createRolePermission($isCreated)
    {
        if (!$isCreated) {
            $this->role->permissions()->sync([$this->permission->id], false);
        }
    }
}
