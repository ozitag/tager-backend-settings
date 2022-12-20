<?php

namespace OZiTAG\Tager\Backend\Settings;

use Illuminate\Foundation\Bus\DispatchesJobs;
use OZiTAG\Tager\Backend\Fields\Base\Type;
use OZiTAG\Tager\Backend\Fields\Enums\FieldType;
use OZiTAG\Tager\Backend\Fields\TypeFactory;
use OZiTAG\Tager\Backend\Fields\Types\RepeaterType;
use OZiTAG\Tager\Backend\Settings\Jobs\GetSettingByKeyJob;
use OZiTAG\Tager\Backend\Settings\Jobs\UpdateSettingValueJob;
use OZiTAG\Tager\Backend\Settings\Utils\TagerSettingsConfig;

class TagerSettings
{
    use DispatchesJobs;

    private function loadType(string $key): ?Type
    {
        $model = $this->dispatch(new GetSettingByKeyJob($key));
        if (!$model) {
            return null;
        }

        if ($model->type == FieldType::Repeater->value) {
            $settingsField = TagerSettingsConfig::getField($key);
            if ($settingsField) {
                $type = $settingsField->getField()->getTypeInstance();
            } else {
                return null;
            }
        } else {
            $type = TypeFactory::create(FieldType::from($model->type));
        }

        $type->loadValueFromDatabase($model->value);

        if (!empty($type->hasFiles())) {
            $scenario = TagerSettingsConfig::getFieldScenario($model->key);
            if (!empty($scenario)) {
                $type->applyFileScenario($scenario);
            }
        }

        return $type;
    }

    public function get($key)
    {
        $type = $this->loadType($key);
        if (!$type) {
            return null;
        }

        return $type->getValue();
    }


    public function getPublicValue($key)
    {
        $type = $this->loadType($key);
        if (!$type) {
            return null;
        }

        return $type->getPublicValue();
    }

    public function set($key, $value)
    {
        $model = $this->dispatch(new GetSettingByKeyJob($key));
        if (!$model) {
            return false;
        }

        $this->dispatch(new UpdateSettingValueJob($model, $value));

        return true;
    }
}
