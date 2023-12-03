<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\GroupMessage
 *
 * @property int $id
 * @property int $group_id
 * @property int $user_id
 * @property string $message
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Group $group
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|GroupMessage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupMessage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupMessage query()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupMessage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupMessage whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupMessage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupMessage whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupMessage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupMessage whereUserId($value)
 * @mixin \Eloquent
 */
class GroupMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'user_id',
        'message',
    ];

    protected $with = [
        'user',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
