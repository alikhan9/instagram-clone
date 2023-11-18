<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

/**
 * App\Models\PostComment
 *
 * @property int $id
 * @property int $post_id
 * @property int $user_id
 * @property string $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CommentLike> $likes
 * @property-read int|null $likes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CommentResponse> $responses
 * @property-read int|null $responses_count
 * @property-read \App\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CommentLike> $userLiked
 * @property-read int|null $user_liked_count
 * @method static \Illuminate\Database\Eloquent\Builder|PostComment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PostComment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PostComment query()
 * @method static \Illuminate\Database\Eloquent\Builder|PostComment whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostComment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostComment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostComment wherePostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostComment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostComment whereUserId($value)
 * @mixin \Eloquent
 */
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
