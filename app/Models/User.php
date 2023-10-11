<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
//EA 10 Oct 2023 - Added permission roles
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Contracts\FilamentUser;

//EA 10 Oct 2023 - Added user filament auth
class User extends Authenticatable implements FilamentUser
{
    //EA 10 Oct 2023 - Added permission roles
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
        'password' => 'hashed',
    ];

    //EA 10 Oct 2023 - Added user filament auth
    public function canAccessFilament(): bool { 
        return $this->hasRole('admin');
        //return str_ends_with($this->email, '@yourdomain.com') && $this->hasVerifiedEmail(); 
    }
}
