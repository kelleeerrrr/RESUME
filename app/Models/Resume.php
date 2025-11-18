<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resume extends Model
{
    protected $fillable = [
        'user_id',
        'fullname',
        'dob',
        'pob',
        'civil_status',
        'specialization',
        'email',
        'phone',
        'address',
        'profile_photo',
        'spotlight_photo',
        'organization',
        'education',
        'interests',
        'skills',
        'programming',
        'projects',
    ];

    protected $casts = [
        'organization' => 'array',
        'education'   => 'array',
        'interests'   => 'array',
        'skills'      => 'array',
        'programming' => 'array',
        'projects'    => 'array',
    ];

    public function awardFiles()
    {
        return $this->hasMany(AwardFile::class, 'resume_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
