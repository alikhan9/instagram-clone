<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $username
 * @property string $phone
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Bookmark> $bookmark
 * @property-read int|null $bookmark_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $followers
 * @property-read int|null $followers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $following
 * @property-read int|null $following_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserMention> $mentions
 * @property-read int|null $mentions_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Post> $posts
 * @property-read int|null $posts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUsername($value)
 * @mixin \Eloquent
 */
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
        'phone',
        'avatar'
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

    /**
     * Defines a one-to-many relationship between User and Post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Gets the bookmarked posts for the user by filtering the posts with bookmark IDs.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|\LaravelIdea\Helper\App\Models\_IH_Post_QB
     */
    public function bookmarks(): \Illuminate\Database\Eloquent\Relations\HasMany|\LaravelIdea\Helper\App\Models\_IH_Post_QB
    {
        return $this->posts()->whereIn('id', $this->bookmark());
    }

    /**
     * Defines a one-to-many relationship between User and Bookmark,
     * selecting only the post_id from bookmarks.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookmark(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Bookmark::class)->select('post_id');
    }

    /**
     * Defines a one-to-many relationship between User and UserMention,
     * selecting post_id where the user is mentioned and the user_id matches the authenticated user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function mentions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserMention::class)->select('post_id')->where('user_id', auth()->id());
    }

    /**
     * Defines a many-to-many relationship for users that the current user is following.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function following(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follows', 'follower', 'followed');
    }

    /**
     * Defines a many-to-many relationship for users that follow the current user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function followers(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follows', 'followed', 'follower');
    }

    /**
     * Checks if the current user is following a specific user.
     *
     * @param User $user
     * @return bool
     */
    public function isFollowing(User $user): bool
    {
        return $this->following()->where('followed', $user->id)->exists();
    }

    /**
     * Gets the contacts where the current user is the initiator,
     * including the receiver of the contact.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function contactsInitiator(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->hasMany(Contact::class, 'initiator')->with('receiver')->get();
    }

    /**
     * Gets the contacts where the current user is the receiver,
     * including the initiator of the contact. Only valid contacts are included.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function contactsReceiver(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->hasMany(Contact::class, 'receiver')->with('initiator')->where('valid', true)->get();
    }

    /**
     * Merges contacts where the current user is either the initiator or receiver.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function contacts()
    {
        return $this->contactsInitiator()->merge($this->contactsReceiver());
    }

    /**
     * Defines a many-to-many relationship between User and Group
     * through the group_members pivot table.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'group_members', 'user_id', 'group_id');
    }


}
