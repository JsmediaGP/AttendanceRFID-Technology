<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LectureHall extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // A lecture hall can have many scheduled classes
    public function classSchedules(): HasMany
    {
        return $this->hasMany(ClassSchedule::class, 'lecture_hall_id');
    }
}
