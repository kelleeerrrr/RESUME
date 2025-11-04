<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resume extends Model
{
    protected $fillable = [
        'fullname', 'dob', 'pob', 'civil_status', 'specialization',
        'email', 'phone', 'address', 'profile_photo',
        'organization', 'education', 'interests', 'skills', 'programming', 'projects'
    ];

    // Cast JSON/text columns to array
    protected $casts = [
        'organization' => 'array',
        'education'   => 'array',
        'interests'   => 'array',
        'skills'      => 'array',
        'programming' => 'array',
        'projects'    => 'array',
    ];

    // Awards are a relationship
    public function awardFiles()
    {
        return $this->hasMany(AwardFile::class, 'resume_id');
    }
}
