<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id'
    ];

    protected $with = [
        'messages',
        'members',
        'owner'
    ];

    public function messages()
    {
        return $this->hasMany(GroupMessage::class, 'group_id', 'id');
    }

    public function members()
    {
        return $this->hasMany(GroupMember::class, 'user_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

}
