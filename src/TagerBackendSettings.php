<?php

namespace OZiTAG\Tager\Backend\Settings;

use Illuminate\Foundation\Bus\DispatchesJobs;
use OZiTAG\Tager\Backend\Fields\FieldFactory;
use OZiTAG\Tager\Backend\Settings\Jobs\GetSettingByKeyJob;

class TagerBackendSettings
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

        return $field->getValue();
    }
}
