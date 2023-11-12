<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'phone'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function bookmarks()
    {
        return $this->posts()->whereIn('id', $this->bookmark());
    }

    public function bookmark()
    {
        return $this->hasMany(Bookmark::class)->select('post_id');
    }

    public function mentions()
    {
        return $this->hasMany(UserMention::class)->select('post_id')->where('user_id', auth()->id());
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower', 'followed');
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'followed', 'follower');
    }

    public function isFollowing(User $user)
    {
        return $this->following()->where('followed', $user->id)->exists();
    }



}
