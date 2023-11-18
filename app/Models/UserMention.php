<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserMention
 *
 * @property int $id
 * @property int $user_id
 * @property int $post_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Post|null $post
 * @method static \Illuminate\Database\Eloquent\Builder|UserMention newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserMention newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserMention query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserMention whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMention whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMention wherePostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMention whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMention whereUserId($value)
 * @mixin \Eloquent
 */
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
