<?php

namespace OZiTAG\Tager\Backend\Settings;

use Illuminate\Foundation\Bus\DispatchesJobs;
use OZiTAG\Tager\Backend\Fields\FieldFactory;
use OZiTAG\Tager\Backend\Settings\Jobs\GetSettingByKeyJob;
use OZiTAG\Tager\Backend\Settings\Jobs\UpdateSettingValueJob;

class TagerSettings
{
    use DispatchesJobs;

    public function get($key)
    {
        $model = $this->dispatch(new GetSettingByKeyJob($key));
        if (!$model) {
            return null;
        }

        $field = FieldFactory::create($model->type);
        $field->setValue($model->value);

        if (!empty($field->hasFiles())) {
            $scenario = TagerSettingsConfig::getFieldParam($model->key, 'scenario');
            if (!empty($scenario)) {
                $field->applyFileScenario($scenario);
            }
        }

        return $field->getValue();
    }

    public function set($key, $value)
    {
        $model = $this->dispatch(new GetSettingByKeyJob($key));
        if (!$model) {
            return false;
        }

        $model = $this->dispatch(new UpdateSettingValueJob($model, $value));

        return true;
    }
}
