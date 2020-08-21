<?php

namespace OZiTAG\Tager\Backend\Settings\Utils;

use OZiTAG\Tager\Backend\Utils\Helpers\ArrayHelper;

class TagerSettingsConfig
{
    /**
     * @return bool
     */
    public static function hasSections()
    {
        $items = \config('tager-settings');

        return ArrayHelper::isAssoc($items);
    }

    public static function getSections()
    {
        if (!self::hasSections()) {
            return [];
        }

        $items = \config('tager-settings');

        return array_keys($items);
    }

    public static function getFields($section = null)
    {
        $items = \config('tager-settings');

        $result = [];

        if (ArrayHelper::isAssoc($items)) {

            if ($section) {
                return $items[$section] ?? [];
            }

            foreach ($items as $section) {
                foreach ($section as $field => $model) {
                    if (!isset($model['key'])) continue;
                    $result[$model['key']] = $model;
                }
            }
            $result = array_values($result);
        } else {
            if ($section) {
                return [];
            }
            $result = $items;
        }

        return $result;
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
