<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Larapacks\Authorization\Traits\ClearsCachedPermissions;
use Larapacks\Authorization\Traits\HasRoles;
use Larapacks\Authorization\Traits\HasUsers;

class Permission extends Model
{
    use HasFactory, HasRoles, HasUsers, ClearsCachedPermissions;

    protected $fillable = ['name', 'label'];

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot(['expires'])->withTimestamps();
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class)
            ->withPivot(['expires'])
            ->withTimestamps()
            ->using(PermissionRole::class);
    }
}
