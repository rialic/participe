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
        $permissionList = $this->permissionRepository->findAll();

        $permissionList->each(function($permission) {
            if ($permission->name !== 'HOME') {
                $isCreated = $this->hasRolePermission('ADMIN', $permission->name);

                $this->createRolePermission($isCreated);
            }
        });

        /* ADJUTOR */
        $isCreated = $this->hasRolePermission('ADJUTOR', 'DASHBOARD');
        $this->createRolePermission($isCreated);

        $isCreated = $this->hasRolePermission('ADJUTOR', 'MENU.DASHBOARD');
        $this->createRolePermission($isCreated);

        $isCreated = $this->hasRolePermission('ADJUTOR', 'MENU.EVENT');
        $this->createRolePermission($isCreated);

        $isCreated = $this->hasRolePermission('ADJUTOR', 'MENU.EVENT-WEBCLASS');
        $this->createRolePermission($isCreated);

        $isCreated = $this->hasRolePermission('ADJUTOR', 'EVENT.WEBCLASS-VIEW');
        $this->createRolePermission($isCreated);

        $isCreated = $this->hasRolePermission('ADJUTOR', 'EVENT.WEBCLASS-CREATE');
        $this->createRolePermission($isCreated);

        $isCreated = $this->hasRolePermission('ADJUTOR', 'EVENT.WEBCLASS-EDIT');
        $this->createRolePermission($isCreated);

        $isCreated = $this->hasRolePermission('ADJUTOR', 'EVENT.WEBCLASS-REPORT');
        $this->createRolePermission($isCreated);

        $isCreated = $this->hasRolePermission('ADJUTOR', 'SETTINGS.USER-PROFILE');
        $this->createRolePermission($isCreated);

        $isCreated = $this->hasRolePermission('ADJUTOR', 'API.USER-ME');
        $this->createRolePermission($isCreated);

        $isCreated = $this->hasRolePermission('ADJUTOR', 'API.EVENT.WEBCLASS-SHOW');
        $this->createRolePermission($isCreated);

        $isCreated = $this->hasRolePermission('ADJUTOR', 'API.EVENT.WEBCLASS-SAVE');
        $this->createRolePermission($isCreated);

        $isCreated = $this->hasRolePermission('ADJUTOR', 'API.EVENT.WEBCLASS-UPDATE');
        $this->createRolePermission($isCreated);

        $isCreated = $this->hasRolePermission('ADJUTOR', 'API.EVENT.WEBCLASS-DELETE');
        $this->createRolePermission($isCreated);

        $isCreated = $this->hasRolePermission('ADJUTOR', 'API.EVENT.WEBCLASS-REPORT');
        $this->createRolePermission($isCreated);

        $isCreated = $this->hasRolePermission('ADJUTOR', 'API.EVENT.WEBCLASS-REPORT-PDF');
        $this->createRolePermission($isCreated);

        $isCreated = $this->hasRolePermission('ADJUTOR', 'API.EVENT.WEBCLASS-REPORT-EXCEL');
        $this->createRolePermission($isCreated);

        $isCreated = $this->hasRolePermission('ADJUTOR', 'API.MACRO-ZONE');
        $this->createRolePermission($isCreated);

        $isCreated = $this->hasRolePermission('ADJUTOR', 'API.MICRO-ZONE');
        $this->createRolePermission($isCreated);

        $isCreated = $this->hasRolePermission('ADJUTOR', 'API.MODULES');
        $this->createRolePermission($isCreated);

        $isCreated = $this->hasRolePermission('ADJUTOR', 'API.DESCS');
        $this->createRolePermission($isCreated);

        $isCreated = $this->hasRolePermission('ADJUTOR', 'API.DASHBOARD');
        $this->createRolePermission($isCreated);

        /* USUÁRIO */
        $isCreated = $this->hasRolePermission('USUARIO', 'HOME');
        $this->createRolePermission($isCreated);

        $isCreated = $this->hasRolePermission('USUARIO', 'SETTINGS.USER-PROFILE');
        $this->createRolePermission($isCreated);

        $isCreated = $this->hasRolePermission('USUARIO', 'API.MODULES');
        $this->createRolePermission($isCreated);

        $isCreated = $this->hasRolePermission('USUARIO', 'API.USER-ME');
        $this->createRolePermission($isCreated);

        $isCreated = $this->hasRolePermission('ADJUTOR', 'API.USER-UPDATE');
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
