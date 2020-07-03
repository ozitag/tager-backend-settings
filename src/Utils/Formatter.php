<?php

namespace OZiTAG\Tager\Backend\Settings\Utils;

use OZiTAG\Tager\Backend\Settings\Enums\SettingType;

class Formatter
{
    public function formatValue($value, $type)
    {
        switch ($type) {
            case SettingType::NUMBER:
                return (int)$value;
            case SettingType::TEXT:
            case SettingType::STRING:
                return is_null($value) ? '' : $value;
            default:
                return $value;
        }
    }
}
