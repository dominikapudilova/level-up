<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    protected $fillable = [
        'name',
        'code_name',
        'description',
        'category_id',
    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function knowledge() {
        return $this->hasMany(Knowledge::class)
            ->orderBy('code_name');
    }
}
