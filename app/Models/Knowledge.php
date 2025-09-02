<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Knowledge extends Model
{

    protected $fillable = [
        'name',
        'code_name',
        'description',
        'subcategory_id',
    ];

    public function subcategory() {
        return $this->belongsTo(Subcategory::class);
    }

    public function courses() {
        return $this->belongsToMany(Course::class);
    }

    /*public function students()
    {
        return $this->belongsToMany(Student::class, 'knowledge_student')
            ->using(KnowledgeStudent::class)
            ->withPivot(['level_id', 'issued_by'])
            ->withTimestamps();
    }*/
}
