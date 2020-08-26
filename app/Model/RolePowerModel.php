<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RolePowerModel extends Model
{
    public $table='rbac_role_power';
    public $primaryKey='id';
    public $guarded = [];
    public $timestamps = false;
}
