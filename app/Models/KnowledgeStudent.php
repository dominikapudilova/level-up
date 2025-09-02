<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class KnowledgeStudent extends Pivot
{
    protected $fillable = [
        'student_id',
        'knowledge_id',
        'level_id',
        'issued_by',
    ];


}
