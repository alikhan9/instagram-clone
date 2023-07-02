<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentResponse extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','post_comment_id','content'];

    protected $with = ['user','updatedCreatedAt'];

    public function user()
    {
        return $this->belongsTo(User::class)->select('id', 'name');
    }

    public function postComment()
    {
        return $this->belongsTo(PostComment::class)->without('likes', 'user', 'responses', 'user_liked')->select('id', 'post_id');
    }

    public function updatedCreatedAt()
    {
        // dd($this);
        // return $this->getAttributes()->created_at->diffToHuman();
    }

}