<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'kiosk_session_id',
        'student_id',
        'present'
    ];

    public function student() {
        return $this->belongsTo(Student::class);
    }

    /*public function kiosk() {
        return $this->belongsTo(KioskSession::class);
    }*/

}
