<?php

namespace OZiTAG\Tager\Backend\Settings\Models;

use Illuminate\Database\Eloquent\Model;

class TagerSettings extends Model
{
    protected $table = 'tager_settings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'key',
        'type',
        'label',
        'value',
        'changed',
        'priority',
        'public'
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('order', function ($builder) {
            $builder->orderBy('priority', 'ASC');
        });
    }
}
