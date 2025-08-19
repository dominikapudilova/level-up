<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Edufield extends Model
{
    protected $fillable = [
        'name',
        'code_name',
        'description',
    ];

    public function categories() {
        return $this->hasMany(Category::class);
    }
}
