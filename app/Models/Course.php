<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'name',
        'code_name',
        'description',
        'grade',
        'compulsory',
    ];

    public function edugroups() {
        return $this->belongsToMany(Edugroup::class);
    }

    public function knowledge() {
        return $this->belongsToMany(Knowledge::class);
    }
}
