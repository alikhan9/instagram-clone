<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Follow
 *
 * @property int $id
 * @property int $follower
 * @property int $followed
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Follow newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Follow newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Follow query()
 * @method static \Illuminate\Database\Eloquent\Builder|Follow whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Follow whereFollowed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Follow whereFollower($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Follow whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Follow whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Follow extends Model
{
    use HasFactory;

    protected $fillable = ['follower','followed'];
}
