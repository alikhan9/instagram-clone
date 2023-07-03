<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Str;

class CommentResponse extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','post_comment_id','content'];

    protected $with = ['user','likes','userLiked'];

    public function user()
    {
        return $this->belongsTo(User::class)->select('id', 'name');
    }

    public function postComment()
    {
        return $this->belongsTo(PostComment::class)->without('likes', 'user', 'responses', 'user_liked')->select('id', 'post_id');
    }

    public function likes() {
        return $this->hasMany(ResponseLike::class);
    }

    public function userLiked()
    {
        return $this->hasMany(ResponseLike::class)->where('user_id', auth()->id());
    }

    public function getCreatedAtAttribute($value)
    {
        // Format to human readable
        return Str::substr(Carbon::parse($value)->diffForHumans(), 7);
    }

}