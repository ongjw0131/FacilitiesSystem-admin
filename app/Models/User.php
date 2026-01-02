<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Domain\Users\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
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
        'password_reset_token',
        'password_reset_expires_at',
        'role',
        'status',
        'profile_picture_file_path',
        'contact_number',
        'major',
        'year_of_graduation',
        'is_deleted',
    ];

    /**
     * The attributes that should be cast to dates.
     *
     * @var list<string>
     */
    protected $dates = [
        'email_verified_at',
        'created_at',
        'updated_at',
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
        ];
    }

    /**
     * Domain role object for behavior and permissions
     */
    private ?UserRole $domainRole = null;

    /**
     * Set the domain role object
     */
    public function setDomainRole(UserRole $role): void
    {
        $this->domainRole = $role;
    }

    /**
     * Get the domain role object
     */
    public function getDomainRole(): ?UserRole
    {
        return $this->domainRole;
    }

    /**
     * Check if user has a specific permission
     */
    public function hasPermission(string $permission): bool
    {
        if ($this->domainRole) {
            return $this->domainRole->hasPermission($permission);
        }
        return false;
    }

    /**
     * Get all society memberships for this user
     */
    public function societyMemberships()
    {
        return $this->hasMany(SocietyUser::class, 'userID', 'id');
    }

    /**
     * Get all societies this user is following
     */
    public function followedSocieties()
    {
        return $this->hasMany(SocietyFollower::class, 'userID', 'id');
    }
}

