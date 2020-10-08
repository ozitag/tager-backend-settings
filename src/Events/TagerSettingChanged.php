<?php

namespace OZiTAG\Tager\Backend\Settings\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TagerSettingChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $key;

    public function __construct($key)
    {
        $this->key = $key;
    }
}
