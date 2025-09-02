<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    public const EXP_PER_LEVEL = 10;
    protected $fillable = [
        'first_name',
        'last_name',
        'birth_date',
        'nickname',
        'access_pin',
        'avatar',
        'background_image',
        'exp',
        'karma',
        'bucks'
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function knowledge()
    {
        return $this->belongsToMany(Knowledge::class, 'knowledge_student')
            ->using(KnowledgeStudent::class)
            ->withPivot(['level_id', 'issued_by'])
            ->withTimestamps();
    }

    public function edugroups() {
        return $this->belongsToMany(Edugroup::class)->orderBy('core', 'desc');
    }

    public function coreEdugroup()
    {
        return $this->belongsTo(Edugroup::class)
            ->where('core', true);
            /*->whereHas('edugroups', function ($q) {
                $q->where('core', true);
            });*/
    }

    /*public function coreEdugroup() {
        return $this->belongsToMany(Edugroup::class)
            ->withAggregate('edugroup', 'core')
            ->where('core', true)
            ->limit(1);
    }*/

    public function getCoreEdugroup(): Edugroup|null
    {
        return $this->edugroups()
            ->where('core', true)
            ->first();
    }

    public function getLevel() {
        $level = 1;
        $exp = $this->exp;

        while ($exp >= self::EXP_PER_LEVEL) {
            $exp -= self::EXP_PER_LEVEL;
            $level++;
        }

        return $level;
    }
}
