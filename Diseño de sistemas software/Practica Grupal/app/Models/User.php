<?php

namespace App\Models;
use App\Models\Address;
use App\Models\CreditCard;


// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

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
        'age',
        'role_type',
        'subscription_type',
        'subscription_expiration_date',
        'about_me',
        'image_profile',
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
     * Get the user's address.
     */
    public function address() 
    {
        return $this->hasOne(Address::class);
    }

    public function creditCard()
    {
        return $this->hasOne(CreditCard::class);
    }

    /**
     * Get the courses imparted by the user.
     */
    public function impartedCourses()
    {
        return $this->hasMany(Course::class);
    }

    public static function getCurrentUser() {
        return User::first();
    }

    public function isAdmin()
    {
        return $this->role_type == 'ADMIN'; // ver que el role se escriba asi
    }

    public function isTeacher()
    {
        return $this->role_type == 'TEACHER'; // ver que el role se escriba asi
    }

    public function hasPremium() 
    {
        return $this->subscription_type == 'PREMIUM';
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
