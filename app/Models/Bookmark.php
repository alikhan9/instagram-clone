<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','post_id'];

    protected $with = ['posts'];

    public function posts()
    {
        return $this->hasMany(Post::class, 'id', 'post_id');
    }
}
