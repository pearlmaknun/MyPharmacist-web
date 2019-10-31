<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    protected $table = 'tb_chat';
    protected $primaryKey = 'chat_id';

    protected $fillable = ['user_id','apoteker_id','waktu_pengajuan','status_chat'];

    public function konseli(){
        return $this->belongsTo(Konseli::class, 'user_id');
    }

    public function apoteker(){
        return $this->belongsTo(Apoteker::class, 'apoteker_id');
    }
}
