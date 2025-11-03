<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resume extends Model
{
    use HasFactory;

    // ==================== Fillable Fields ==================== //
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
        'profile_photo', // <--- Added for profile image upload
    ];

    // ==================== Cast JSON fields to array ==================== //
    protected $casts = [
        'organization' => 'array',
        'interests' => 'array',
        'education' => 'array',
        'skills' => 'array',
        'programming' => 'array', // Cast to array for safe foreach
        'projects' => 'array',
        'awards' => 'array',
    ];

    // ==================== Relationship to User ==================== //
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
