<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Str;

/**
 * App\Models\CommentResponse
 *
 * @property int $id
 * @property int $post_comment_id
 * @property int $user_id
 * @property string $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ResponseLike> $likes
 * @property-read int|null $likes_count
 * @property-read \App\Models\PostComment $postComment
 * @property-read \App\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ResponseLike> $userLiked
 * @property-read int|null $user_liked_count
 * @method static \Illuminate\Database\Eloquent\Builder|CommentResponse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CommentResponse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CommentResponse query()
 * @method static \Illuminate\Database\Eloquent\Builder|CommentResponse whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommentResponse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommentResponse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommentResponse wherePostCommentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommentResponse whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommentResponse whereUserId($value)
 * @mixin \Eloquent
 */
class CommentResponse extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','post_comment_id','content'];

    protected $with = ['user','likes','userLiked'];

    public function user()
    {
        return $this->belongsTo(User::class)->select('id', 'name','username','avatar');
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
