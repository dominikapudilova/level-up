<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use SoftDeletes;

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

        $expPerLevel = config('school.economy.exp_per_level', 10);
        while ($exp >= $expPerLevel) {
            $exp -= $expPerLevel;
            $level++;
        }

        return $level;
    }

    public function getExpToNextLevel() {
        $exp = $this->exp;
        $level = $this->getLevel();

        return ($level * config('school.economy.exp_per_level', 10)) - $exp;
    }
}
