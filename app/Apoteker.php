<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Apoteker extends Model
{
    protected $table = 'tb_apoteker';
    protected $primaryKey = 'apoteker_id';

    protected $fillable = ['apoteker_nik','apoteker_name','apoteker_email','apoteker_number','apoteker_address'];
}
