<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    use HasFactory;

    protected $table = 'students';

    protected $fillable = [
        'user_id',
        'student_number',
        'department_id',
        'enrollment_year',
        'gender',
    ];

    protected function casts(): array
    {
        return [
            'enrollment_year' => 'integer',
        ];
    }

        public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
