<?php

namespace App\Models;

use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Laravel\Jetstream\HasProfilePhoto;
use Illuminate\Support\Facades\Storage;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements FilamentUser, HasAvatar, MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'socialite_provider',
        'socialite_id',
        'phone',
        'fiscal_code',
        'vat',
        'last_seen'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
        'socialite_provider',
        'socialite_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'last_seen' => 'datetime'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function canAccessFilament(): bool
    {
        return $this->is_admin ?? false;
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->profile_photo_url;
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function shippingAddresses()
    {
        return $this->hasMany(Address::class)->where('billing',0);
    }

    public function billingAddresses()
    {
        return $this->hasMany(Address::class)->where('billing',1);
    }

    public function defaultAddress()
    {
        return $this->hasOne(Address::class)->where('default',1)->where('billing',false)->latest();
    }

    public function defaultBillingAddress()
    {
        return $this->hasOne(Address::class)->where('default',1)->where('billing',true)->latest();
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function placedOrders()
    {
        return $this->hasMany(Order::class)->placed();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    protected function email(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => strtolower($value),
            set: fn ($value) => strtolower($value),
        );
    }

    protected function fiscalCode(): Attribute
    {
        return Attribute::make(
            set: function ($value) {
                return strtoupper($value);
            },
        );
    }

    /**
     * Get the default profile photo URL if no profile photo has been uploaded.
     *
     * @return string
     */
    protected function defaultProfilePhotoUrl()
    {
        return 'https://robohash.org/'.md5(urlencode(Str::lower($this->email))).'.png?bgset=bg1';
    }
}
