<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Course extends Model
{
    protected $table = 'courses';

    protected $fillable = [
        'code',
        'name',
    ];

    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'course_department');
    }
}
