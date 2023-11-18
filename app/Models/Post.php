<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * App\Models\Post
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $description
 * @property string|null $location
 * @property string|null $image
 * @property string|null $video
 * @property int $enable_comments
 * @property int $enable_likes
 * @property string|null $image_description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PostComment> $comments
 * @property-read int|null $comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $likes
 * @property-read int|null $likes_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Post newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Post newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Post query()
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereEnableComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereEnableLikes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereImageDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereVideo($value)
 * @mixin \Eloquent
 */
class Post extends Model
{
    use HasFactory;

    protected $fillable = ['user_id',
    'description','location','video',
    'image','enable_comments','enable_likes','image_description'];

    protected $with = ['user'];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->select('id', 'name', 'email');
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'post_likes')->latest();
    }

    public function userLiked()
    {
        return $this->belongsToMany(User::class, 'post_likes')->where('user_id', auth()->id())->count() > 0;
    }

    public function userBookmarked()
    {
        return $this->hasMany(Bookmark::class, 'post_id', 'id')->where('user_id', auth()->id())->count() > 0;
    }

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
