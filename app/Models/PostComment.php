<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class PostComment extends Model
{
    use HasFactory;

    protected $fillable = ['post_id','user_id','content','updated_created_at'];

    protected $with = ['user','likes','userLiked','responses'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->select('id', 'name');
    }

    public function likes()
    {
        return $this->hasMany(CommentLike::class, 'post_comment_id');
    }

    public function userLiked()
    {
        return $this->hasMany(CommentLike::class, 'post_comment_id')->where('user_id', auth()->id());
    }

    public function responses()
    {
        return $this->hasMany(CommentResponse::class);
    }

    public function getCreatedAtAttribute($value)
    {
        // Format to human readable
        return Str::substr(Carbon::parse($value)->diffForHumans(), 7);
    }
}
