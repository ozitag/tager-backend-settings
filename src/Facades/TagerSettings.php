<?php

namespace OZiTAG\Tager\Backend\Settings\Facades;

use Illuminate\Support\Facades\Facade;

class TagerSettings extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'settings';
    }
}

