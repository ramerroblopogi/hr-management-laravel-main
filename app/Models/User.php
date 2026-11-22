<?php

namespace App\Models;

use App\Http\Filters\V1\QueryFilter;
use App\Permissions\V1\Abilities;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

/**
 * A user who may have written products (an "author").
 * Fillable lists the columns that may be mass assigned with User::create([...]).
 */
#[Fillable(['name', 'email', 'password', 'is_manager'])]
#[Hidden(['password', 'remember_token'])]
class User extends Model implements JWTSubject
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_manager',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            // Returns true/false instead of 1/0
            'is_manager' => 'boolean',
        ];
    }

    /**
     * The products written by this user (one user has many products).
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * The value stored inside the token to identify the user (the user id).
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Extra data stored in the token payload: the user's abilities.
     * They are written into the JWT every time the user logs in.
     */
    public function getJWTCustomClaims()
    {
        return [
            'abilities' => Abilities::getAbilities($this),
        ];
    }

    /**
     * Query scope: User::filter($filters) applies the filters sent in the URL.
     */
    public function scopeFilter(Builder $builder, QueryFilter $filters): Builder
    {
        return $filters->apply($builder);
    }

    /**
     * Checks an ability stored in the current JWT, for example 'product:update'.
     * Sanctum's own tokenCan() does not read JWT claims, so we implement it here.
     */
    public function tokenCan(string $ability): bool
    {
        try {
            // Read the "abilities" claim of the token sent with this request
            $abilities = auth('api')->payload()->get('abilities') ?? [];
        } catch (\Throwable $e) {
            // No token, or an unreadable one: no abilities
            return false;
        }

        return in_array($ability, $abilities, true);
    }
}