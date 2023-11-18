<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ResponseLike
 *
 * @property int $id
 * @property int $user_id
 * @property int $comment_response_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ResponseLike newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ResponseLike newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ResponseLike query()
 * @method static \Illuminate\Database\Eloquent\Builder|ResponseLike whereCommentResponseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResponseLike whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResponseLike whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResponseLike whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResponseLike whereUserId($value)
 * @mixin \Eloquent
 */
class ResponseLike extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','comment_response_id'];
}