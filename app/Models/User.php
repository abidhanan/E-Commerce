<?php

namespace App\Models;

use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, HasRoles, MustVerifyEmailTrait, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'gender',
        'date_of_birth',
        'email_verified_at',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function sendEmailVerificationNotification()
    {
        $this->notify(new \App\Notifications\VerifyEmailCustom);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function wishlistProducts()
    {
        return $this->belongsToMany(Product::class, 'wishlists')
            ->withTimestamps();
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderReviews()
    {
        return $this->hasMany(OrderReview::class);
    }

    public function orderComplaints()
    {
        return $this->hasMany(OrderComplaint::class);
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
