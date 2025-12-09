<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class KnowledgeStudent extends Pivot
{
    protected $fillable = [
        'student_id',
        'knowledge_id',
        'level_id',
        'kiosk_id',
        'issued_by',
    ];

    public function level() :\Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(KnowledgeLevel::class, 'level_id');
    }
}
