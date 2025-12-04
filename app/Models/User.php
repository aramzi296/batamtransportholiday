<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
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
        'role',
        'nomor_whatsapp',
        'foto_profil',
        'data_anggota',
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
            'data_anggota' => 'array',
        ];
    }

    public function articles()
    {
        return $this->hasMany(Article::class, 'author_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isCustomer()
    {
        return $this->role === 'customer';
    }

    public function isMember()
    {
        return $this->role === 'member';
    }

    /**
     * Get foto profil URL
     */
    public function getFotoProfilUrlAttribute()
    {
        if ($this->foto_profil) {
            // Cek di public storage
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($this->foto_profil)) {
                return \Illuminate\Support\Facades\Storage::disk('public')->url($this->foto_profil);
            }
        }
        
        // Fallback ke avatar default
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&size=150&background=17a2b8&color=ffffff&bold=true';
    }
}
