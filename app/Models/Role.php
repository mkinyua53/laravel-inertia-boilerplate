<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Larapacks\Authorization\Traits\ManagesPermissions;

class Role extends Model
{
    use HasFactory, ManagesPermissions;

    protected $fillable = [
        'name', 'label', 'default'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot(['expires'])->withTimestamps();
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class)
            ->withPivot(['expires'])
            ->withTimestamps()
            ->using(PermissionRole::class);
    }
}
