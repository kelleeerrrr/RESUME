<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AwardFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'resume_id',
        'award_name',
        'file_path',
    ];

    // Relationship to Resume
    public function resume()
    {
        return $this->belongsTo(Resume::class);
    }
}
