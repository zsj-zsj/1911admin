<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AdminRoleModel extends Model
{
    protected $table='rbac_admin_role';
    protected $primaryKey='id';
    protected $guarded = [];
    public $timestamps = false;
}
