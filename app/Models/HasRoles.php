<?php

namespace App\Models;

trait HasRoles {

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function isHomeOwner()
    {
        return $this->hasRole('condo_owner');
    }

    public function assignRole($role)
    {
        return $this->roles()->save(
            Role::whereName($role)->firstOrFail()
        );
    }

    public function removeRole($role)
    {
        $this->roles()->detach(
            Role::whereName($role)->firstOrFail()
        );
    }

    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->roles->contains('name', $role);
        }

        return !! $role->intersect($this->roles)->count();
    }

    public function hasPermission(Permission $permission)
    {
        return $this->hasRole($permission->roles);
    }
}
