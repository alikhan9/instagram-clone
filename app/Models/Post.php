<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['user_id',
    'description','location','video',
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

    public function userLiked()
    {
        return $this->belongsToMany(User::class, 'post_likes')->where('user_id', auth()->id())->count() > 0;
    }

    // public function userBookmarked()
    // {
    //     return $this->hasMany(Bookmark::class, 'post_id', 'id')->where('user_id', auth()->id());
    // }

    public function comments()
    {
        return $this->hasMany(PostComment::class)->latest();
    }

    public function getCreatedAtAttribute($value)
    {
        // Format to human readable
        return Str::substr(Carbon::parse($value)->diffForHumans(), 7);
    }

}
