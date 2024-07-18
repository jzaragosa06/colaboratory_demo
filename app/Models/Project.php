<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    // protected $fillable = ['name', 'description', 'owner_id'];

    // public function users()
    // {
    //     return $this->belongsToMany(User::class)->withPivot('role')->withTimestamps();
    // }

    // public function owner()
    // {
    //     return $this->belongsTo(User::class, 'owner_id');
    // }


    protected $fillable = ['name', 'description', 'owner_id'];

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('role', 'invitation_status')
            ->withTimestamps();
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

}
