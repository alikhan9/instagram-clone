<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PostLike
 *
 * @property int $id
 * @property int $post_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PostLike newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PostLike newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PostLike query()
 * @method static \Illuminate\Database\Eloquent\Builder|PostLike whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostLike whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostLike wherePostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostLike whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostLike whereUserId($value)
 * @mixin \Eloquent
 */
class PostLike extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','post_id'];
}