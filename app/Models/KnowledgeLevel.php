<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KnowledgeLevel extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'name',
        'description',
        'weight',
        'icon'
    ];
}
