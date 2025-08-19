<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'code_name',
        'description',
        'edufield_id',
    ];

    public function edufield() {
        return $this->belongsTo(Edufield::class);
    }

    public function subcategories() {
        return $this->hasMany(Subcategory::class);
    }
}
