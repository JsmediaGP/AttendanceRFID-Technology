<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['course_code', 'name', 'lecturer_id'];

    // A course belongs to a lecturer
    public function lecturer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'lecturer_id');
    }

    // A course can have many class schedules
    public function classSchedules(): HasMany
    {
        return $this->hasMany(ClassSchedule::class, 'course_id');
    }
}
