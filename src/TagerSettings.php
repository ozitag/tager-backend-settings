<?php

namespace OZiTAG\Tager\Backend\Settings;

use Illuminate\Foundation\Bus\DispatchesJobs;
use OZiTAG\Tager\Backend\Fields\Enums\FieldType;
use OZiTAG\Tager\Backend\Fields\TypeFactory;
use OZiTAG\Tager\Backend\Settings\Jobs\GetSettingByKeyJob;
use OZiTAG\Tager\Backend\Settings\Jobs\UpdateSettingValueJob;
use OZiTAG\Tager\Backend\Settings\Utils\TagerSettingsConfig;

class TagerSettings
{
    use DispatchesJobs;

    public function get($key)
    {
        $model = $this->dispatch(new GetSettingByKeyJob($key));
        if (!$model) {
            return null;
        }

        $type = TypeFactory::create(FieldType::from($model->type));
        $type->setValue($model->value);

        if (!empty($type->hasFiles())) {
            $scenario = TagerSettingsConfig::getFieldScenario($model->key);
            if (!empty($scenario)) {
                $type->applyFileScenario($scenario);
            }
        }

        return $type->getValue();
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
