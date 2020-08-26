<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AdminModel extends Model
{
    protected $table='rbac_admin';
    protected $primaryKey='admin_id';
    protected $guarded = [];
    public $timestamps = false;
}
