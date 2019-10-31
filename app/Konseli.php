<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Konseli extends Model
{
    protected $table = 'tb_user';
    protected $primaryKey = 'user_id';

    protected $fillable = ['user_name'];
}
