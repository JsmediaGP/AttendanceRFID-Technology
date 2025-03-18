<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClassSchedule extends Model
{
    use HasFactory;

    protected $fillable = ['course_id', 'start_time', 'end_time', 'day', 'status', 'lecture_hall_id'];

    // A class schedule belongs to a course
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    // A class schedule belongs to a lecture hall
    public function lectureHall(): BelongsTo
    {
        return $this->belongsTo(LectureHall::class, 'lecture_hall_id');
    }

    // A class schedule can have multiple attendance records
    public function attendanceRecords(): HasMany
    {
        return $this->hasMany(AttendanceRecord::class, 'class_id');
    }
}
