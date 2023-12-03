<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Contact
 *
 * @property int $id
 * @property \App\Models\User $initiator
 * @property \App\Models\User $receiver
 * @property int $valid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Contact newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Contact newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Contact query()
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereInitiator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereReceiver($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereValid($value)
 * @mixin \Eloquent
 */
class Contact extends Model
{
    use HasFactory;

    protected $fillable = ['receiver','initiator','valid'];


    public function initiator()
    {
        return $this->belongsTo(User::class, 'initiator')->select(['id','username','name']);
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver')->select(['id','username','name']);
    }

}
