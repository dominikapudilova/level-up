<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Edugroup extends Model
{
    protected $fillable = [
        'name',
        'year_founded',
        'core'
    ];

    protected $casts = [
        'core' => 'boolean'
    ];

    public function students() {
        return $this->belongsToMany(Student::class);
    }

    public function courses() {
        return $this->belongsToMany(Course::class);
    }
}
