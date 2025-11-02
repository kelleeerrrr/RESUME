<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resume extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'fullname',
        'about',
        'dob',
        'pob',
        'civil_status',
        'specialization',
        'email',
        'phone',
        'address',
        'organization',
        'interests',
        'education',
        'skills',
        'programming', // Added programming languages
        'projects',
        'awards',
    ];

    protected $casts = [
        'organization' => 'array',
        'interests' => 'array',
        'education' => 'array',
        'skills' => 'array',
        'programming' => 'array', // Cast to array for safe foreach
        'projects' => 'array',
        'awards' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}


