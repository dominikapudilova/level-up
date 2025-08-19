<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
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
}
