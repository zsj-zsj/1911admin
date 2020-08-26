<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RoleModel extends Model
{
    protected $table='rbac_role';
    protected $primaryKey='role_id';
    protected $guarded = [];
    public $timestamps = false;
}
