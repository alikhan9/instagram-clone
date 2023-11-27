<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = ['receiver','initiator','valid'];


    public function initiator()
    {
        return $this->belongsTo(User::class, 'initiator')->select(['id','username','name']);
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver')->select(['id','username','name']);
    }

}
