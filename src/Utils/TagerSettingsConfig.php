<?php

namespace OZiTAG\Tager\Backend\Settings\Utils;

use OZiTAG\Tager\Backend\Fields\Base\Field;
use OZiTAG\Tager\Backend\Fields\Enums\FieldType;
use OZiTAG\Tager\Backend\Fields\FieldFactory;
use OZiTAG\Tager\Backend\Fields\Fields\RepeaterField;
use OZiTAG\Tager\Backend\Fields\Fields\StringField;
use OZiTAG\Tager\Backend\Settings\Structures\TagerSettingField;
use OZiTAG\Tager\Backend\Utils\Helpers\ArrayHelper;
use yii\db\Exception;

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

    /**
     * @param $fieldData
     * @return TagerSettingField|null
     * @throws \OZiTAG\Tager\Backend\Fields\Exceptions\InvalidTypeException
     */
    private static function parseField($fieldData)
    {
        if (is_string($fieldData)) {
            return new TagerSettingField(new StringField($fieldData), null);
        }

        if (is_array($fieldData)) {
            if (!isset($fieldData['label'])) return null;

            $field = FieldFactory::create(
                $fieldData['type'] ?? FieldType::String,
                $fieldData['label'],
                $fieldData['meta'] ?? []
            );

            if ($field instanceof RepeaterField) {
                $field->setFields($fieldData['fields'] ?? []);
                $field->setViewMode($fieldData['viewMode'] ?? null);
            }

            return new TagerSettingField(
                $field,
                $fieldData['value'] ?? null,
                $fieldData['private'] ?? false
            );

        }

        return null;
    }

    /**
     * @param $fieldsData
     * @return TagerSettingField[]
     * @throws \OZiTAG\Tager\Backend\Fields\Exceptions\InvalidTypeException
     */
    private static function parseFields($fieldsData)
    {
        $result = [];

        foreach ($fieldsData as $key => $field) {
            $result[$key] = self::parseField($field);
        }

        return $result;
    }

    /**
     * @param null $section
     * @return TagerSettingField[]
     * @throws \OZiTAG\Tager\Backend\Fields\Exceptions\InvalidTypeException
     */
    public static function getFields($section = null)
    {
        $items = self::config();

        if (self::hasSections() == false && $section) {
            return [];
        }

        if ($section) {
            return self::parseFields($items[$section]) ?? [];
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
            return self::parseFields($items);
        }
    }

    public static function getField($key)
    {
        $fields = self::getFields();

        foreach ($fields as $fieldKey => $field) {
            if ($fieldKey === $key) {
                return $field;
            }
        }

        return null;
    }

    public static function getFieldScenario($key)
    {
        $field = self::getField($key);

        if ($field && $field->getField()->getTypeInstance()->hasFiles()) {
            return $field->getField()->getMetaParamValue('scenario');
        }

        return null;
    }
}
