<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Connection extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'status',
    ];


    public function sender()
    {
        return $this->hasMany(User::class,'id','sender_id');
    }

    public function receiver()
    {
        return $this->hasMany(User::class,'id','receiver_id');
    }
}
