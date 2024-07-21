<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileAssociation extends Model
{
    use HasFactory;
    protected $fillable = ['file_id', 'associated_file_path', 'associated_by'];

    public function file()
    {
        return $this->belongsTo(File::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'associated_by');
    }
}
