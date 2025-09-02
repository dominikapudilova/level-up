<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KioskSession extends Model
{
    protected $fillable = [
        'teacher_id',
        'edugroup_id',
        'course_id',
        'started_at',
        'ended_at'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function teacher() {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function edugroup() {
        return $this->belongsTo(Edugroup::class);
    }

    public function course() {
        return $this->belongsTo(Course::class);
    }

    public function attendances() {
        return $this->hasMany(Attendance::class);
    }
}
