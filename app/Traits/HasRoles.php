<?php

namespace App\Traits;

trait HasRoles
{
    public function hasRole($role): bool
    {
        if (isset($this->role)) {
            return $this->role === $role;
        }

        if (method_exists($this, 'roles')) {
            return $this->roles()->where('name', $role)->exists();
        }

        return false;
    }

    public function hasAnyRole(array $roles): bool
    {
        foreach ($roles as $role) {
            if ($this->hasRole($role)) {
                return true;
            }
        }
        return false;
    }

    public function hasAllRoles(array $roles): bool
    {
        foreach ($roles as $role) {
            if (!$this->hasRole($role)) {
                return false;
            }
        }
        return true;
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super_admin');
    }

    public function isModerator(): bool
    {
        return $this->hasRole('moderator');
    }

    public function assignRole($role)
    {
        if (property_exists($this, 'role')) {
            $this->update(['role' => $role]);
            return;
        }

        if (method_exists($this, 'roles')) {
            $this->roles()->syncWithoutDetaching($role);
        }
    }

    public function removeRole($role)
    {
        if (property_exists($this, 'role') && $this->role === $role) {
            $this->update(['role' => null]);
            return;
        }

        if (method_exists($this, 'roles')) {
            $this->roles()->detach($role);
        }
    }

    public function getRoles()
    {
        if (isset($this->role)) {
            return [$this->role];
        }

        if (method_exists($this, 'roles')) {
            return $this->roles()->pluck('name')->toArray();
        }

        return [];
    }
}
