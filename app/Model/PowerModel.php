<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PowerModel extends Model
{
    protected $table='rbac_power';
    protected $primaryKey='power_id';
    protected $guarded = [];
    public $timestamps = false;
}
