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
}
