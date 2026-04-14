<?php

namespace App\Models;

use Spatie\Permission\Traits\HasRoles;


// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements CanResetPassword
{

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'specialiteit',
        'photo',
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
     * De "booted" methode van de model.
     */
    protected static function booted()
    {
        static::created(function ($user) {
            // Controleer of de rol 'klant' bestaat, anders wordt deze aangemaakt
            // (Dit voorkomt errors als je vergeet te seeden)
            if (!\Spatie\Permission\Models\Role::where('name', 'klant')->exists()) {
                \Spatie\Permission\Models\Role::create(['name' => 'klant']);
            }

            // Wijs de rol 'klant' toe aan de nieuwe gebruiker
            $user->assignRole('klant');
        });
    }
}
