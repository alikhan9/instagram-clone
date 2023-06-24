<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['user_id',
    'description','location',
    'image','enable_comments','enable_likes','image_description'];

    protected $with = ['user','likes','comments'];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->select('id', 'name', 'email');
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'post_likes')->select('user_id')->withTimestamps();
    }

    public function comments() {
        return $this->hasMany(PostComment::class)
        ->orderByDesc('created_at');
    }

}