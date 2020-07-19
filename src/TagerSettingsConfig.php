<?php

namespace OZiTAG\Tager\Backend\Settings;

class TagerSettingsConfig
{
    public static function getFields()
    {
        return \config('tager-settings');
    }

    public static function getField($key)
    {
        $fields = self::getFields();

        foreach ($fields as $field) {
            if ($field['key'] === $key) {
                return $field;
            }
        }

        return null;
    }

    public static function getFieldParam($key, $param)
    {
        $field = self::getField($key);

        if (!$field || !isset($field['params'][$param])) {
            return null;
        }

        return $field['params'][$param];
    }
}
