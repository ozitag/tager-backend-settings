<?php

namespace OZiTAG\Tager\Backend\Settings;

use Illuminate\Foundation\Bus\DispatchesJobs;
use OZiTAG\Tager\Backend\Settings\Jobs\GetSettingByKeyJob;
use OZiTAG\Tager\Backend\Settings\Utils\Formatter;

class TagerBackendSettings
{
    use DispatchesJobs;

    public function get($key)
    {
        $model = $this->dispatch(new GetSettingByKeyJob($key));
        if (!$model) {
            return null;
        }

        $formatter = new Formatter();
        return $formatter->formatValue($model->value, $model->type);
    }
}
