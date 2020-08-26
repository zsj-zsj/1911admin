<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class NewCateModel extends Model
{
    protected $table='new_category';
    protected $primaryKey='cart_id';
    protected $guarded = [];
    public $timestamps = false;
}
