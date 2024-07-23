<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    // protected $fillable = ['user_id', 'project_id', 'filename', 'path'];

    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }

    // public function project()
    // {
    //     return $this->belongsTo(Project::class);
    // }

    // protected $fillable = ['user_id', 'project_id', 'filename', 'path'];

    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }

    // public function project()
    // {
    //     return $this->belongsTo(Project::class);
    // }

    // public function associations()
    // {
    //     return $this->hasMany(FileAssociation::class);
    // }
    protected $fillable = ['user_id', 'project_id', 'filename', 'path'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function associations()
    {
        return $this->hasMany(FileAssociation::class);
    }
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
