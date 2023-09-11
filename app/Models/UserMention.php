<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMention extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','post_id'];

    protected $with = ['post'];

    public function post()
    {
        return $this->hasOne(Post::class, 'id', 'post_id');
    }
}
