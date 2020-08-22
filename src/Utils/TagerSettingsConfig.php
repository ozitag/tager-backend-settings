<?php

namespace OZiTAG\Tager\Backend\Settings\Utils;

use OZiTAG\Tager\Backend\Utils\Helpers\ArrayHelper;

class TagerSettingsConfig
{
    /**
     * @return array
     */
    private static function config()
    {
        return \config('tager-settings', []);
    }

    /**
     * @return bool
     */
    public static function hasSections()
    {
        $items = self::config();
        if (empty($items)) {
            return false;
        }

        $firstValue = array_shift($items);
        if (isset($firstValue['type']) && isset($firstValue['label'])) {
            return false;
        }

        return true;
    }

    public static function getSections()
    {
        if (!self::hasSections()) {
            return [];
        }

        return array_keys(self::config());
    }

    public static function getFields($section = null)
    {
        $items = self::config();

        if (self::hasSections() == false && $section) {
            return [];
        }

        if ($section) {
            return $items[$section] ?? [];
        }

        if (self::hasSections()) {
            $result = [];

            foreach (self::getSections() as $section) {
                foreach (self::getFields($section) as $key => $field) {
                    $result[$key] = $field;
                }
            }

            return $result;
        } else {
            return $items;
        }
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
