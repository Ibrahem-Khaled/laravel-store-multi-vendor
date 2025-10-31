<?php

namespace App\Traits;

use App\Models\Permission;
use App\Models\Role;

trait HasRolesAndPermissions
{
    /**
     * التحقق من وجود دور معين
     */
    public function hasRole(string|array $roles): bool
    {
        if (is_string($roles)) {
            $roles = [$roles];
        }

        // للتوافق مع النظام القديم (حقل role في users)
        if (isset($this->role) && in_array($this->role, $roles)) {
            return true;
        }

        // التحقق من الأدوار الجديدة
        return $this->roles()->whereIn('name', $roles)->exists();
    }

    /**
     * التحقق من وجود أي دور من الأدوار المحددة
     */
    public function hasAnyRole(array $roles): bool
    {
        return $this->hasRole($roles);
    }

    /**
     * التحقق من وجود جميع الأدوار المحددة
     */
    public function hasAllRoles(array $roles): bool
    {
        foreach ($roles as $role) {
            if (!$this->hasRole($role)) {
                return false;
            }
        }
        return true;
    }

    /**
     * التحقق من وجود صلاحية معينة
     */
    public function hasPermission(string|Permission $permission): bool
    {
        // إذا كان admin، لديه جميع الصلاحيات
        if ($this->hasRole('admin')) {
            return true;
        }

        $permissionName = $permission instanceof Permission ? $permission->name : $permission;

        // الحصول على جميع الصلاحيات من الأدوار
        $userPermissions = $this->roles()
            ->with('permissions')
            ->get()
            ->pluck('permissions')
            ->flatten()
            ->pluck('name')
            ->toArray();

        return in_array($permissionName, $userPermissions);
    }

    /**
     * التحقق من وجود أي صلاحية من الصلاحيات المحددة
     */
    public function hasAnyPermission(array|string $permissions): bool
    {
        if (is_string($permissions)) {
            $permissions = [$permissions];
        }

        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * التحقق من وجود جميع الصلاحيات المحددة
     */
    public function hasAllPermissions(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }
        return true;
    }

    /**
     * إضافة دور للمستخدم
     */
    public function assignRole(string|Role|array $roles): void
    {
        if (is_string($roles) || $roles instanceof Role) {
            $roles = [$roles];
        }

        foreach ($roles as $role) {
            $roleModel = $role instanceof Role ? $role : Role::where('name', $role)->first();
            if ($roleModel && !$this->roles()->where('roles.id', $roleModel->id)->exists()) {
                $this->roles()->attach($roleModel->id);
            }
        }
    }

    /**
     * إزالة دور من المستخدم
     */
    public function removeRole(string|Role|array $roles): void
    {
        if (is_string($roles) || $roles instanceof Role) {
            $roles = [$roles];
        }

        foreach ($roles as $role) {
            $roleModel = $role instanceof Role ? $role : Role::where('name', $role)->first();
            if ($roleModel) {
                $this->roles()->detach($roleModel->id);
            }
        }
    }

    /**
     * إزالة جميع الأدوار وإضافة أدوار جديدة
     */
    public function syncRoles(array $roles): void
    {
        $roleIds = [];
        foreach ($roles as $role) {
            $roleModel = $role instanceof Role ? $role : Role::where('name', $role)->first();
            if ($roleModel) {
                $roleIds[] = $roleModel->id;
            }
        }
        $this->roles()->sync($roleIds);
    }

    /**
     * الحصول على جميع الصلاحيات
     */
    public function getAllPermissions(): array
    {
        if ($this->hasRole('admin')) {
            return Permission::pluck('name')->toArray();
        }

        return $this->roles()
            ->with('permissions')
            ->get()
            ->pluck('permissions')
            ->flatten()
            ->pluck('name')
            ->unique()
            ->toArray();
    }
}

