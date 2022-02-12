<?php

namespace OZiTAG\Tager\Backend\Settings\Models;

use OZiTAG\Tager\Backend\Core\Models\TModel;

class TagerSettings extends TModel
{
    protected $table = 'tager_settings';

    static string $defaultOrder = 'priority ASC';

    protected $fillable = [
        'key', 'type', 'label', 'value', 'changed', 'priority', 'public'
    ];
}
