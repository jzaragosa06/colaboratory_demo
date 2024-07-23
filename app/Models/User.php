<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;


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
        'email',
        'password',
        'profile_photo',
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

    // public function projects()
    // {
    //     return $this->belongsToMany(Project::class)->withPivot('role')->withTimestamps();
    // }


    public function projects()
    {
        return $this->belongsToMany(Project::class)
            ->withPivot('role', 'invitation_status')
            ->withTimestamps();
    }

    public function pendingInvitations()
    {
        return $this->projects()->wherePivot('invitation_status', 'pending');
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function getProfilePhotoUrlAttribute()
    {
        return $this->profile_photo_path ? Storage::disk('public')->url($this->profile_photo_path) : 'path/to/default/photo.jpg';
    }
}
