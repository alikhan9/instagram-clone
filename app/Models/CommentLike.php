<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CommentLike
 *
 * @property int $id
 * @property int $post_comment_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CommentLike newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CommentLike newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CommentLike query()
 * @method static \Illuminate\Database\Eloquent\Builder|CommentLike whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommentLike whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommentLike wherePostCommentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommentLike whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommentLike whereUserId($value)
 * @mixin \Eloquent
 */
class CommentLike extends Model
{
    use HasFactory;

    protected $fillable = ['post_comment_id','user_id'];

}