<?php

namespace App\Traits;

trait HasPermissions
{
    public function hasPermission($permission): bool
    {
        return $this->role->permissions->contains('slug', $permission);
    }

    public function hasAnyPermission($permissions): bool
    {
        if (is_array($permissions)) {
            foreach ($permissions as $permission) {
                if ($this->hasPermission($permission)) {
                    return true;
                }
            }
        } else {
            if ($this->hasPermission($permissions)) {
                return true;
            }
        }

        return false;
    }

    public function hasAllPermissions($permissions): bool
    {
        if (is_array($permissions)) {
            foreach ($permissions as $permission) {
                if (!$this->hasPermission($permission)) {
                    return false;
                }
            }
        } else {
            if (!$this->hasPermission($permissions)) {
                return false;
            }
        }

        return true;
    }
}