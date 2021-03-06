<?php

namespace OZiTAG\Tager\Backend\Settings\Jobs;

use Illuminate\Http\Exceptions\HttpResponseException;
use OZiTAG\Tager\Backend\Core\Jobs\Job;
use OZiTAG\Tager\Backend\Fields\Fields\RepeaterField;
use OZiTAG\Tager\Backend\Fields\TypeFactory;
use OZiTAG\Tager\Backend\Fields\Types\RepeaterType;
use OZiTAG\Tager\Backend\HttpCache\HttpCache;
use OZiTAG\Tager\Backend\Settings\Events\TagerSettingChanged;
use OZiTAG\Tager\Backend\Settings\Events\TagerSettingsChanged;
use OZiTAG\Tager\Backend\Settings\Models\TagerSettings;
use OZiTAG\Tager\Backend\Settings\Utils\TagerSettingsConfig;

class UpdateSettingValueJob extends Job
{
    private $model;

    private $value;

    public function __construct(TagerSettings $model, $value)
    {
        $this->model = $model;
        $this->value = $value;
    }

    private function responseError($code, $message)
    {
        throw new HttpResponseException(response()->json([
            'errors' => [
                'value' => [
                    'code' => $code,
                    'message' => $message
                ]
            ],
        ], 400));
    }

    public function handle(HttpCache $httpCache)
    {
        $configField = TagerSettingsConfig::getField($this->model->key);
        if (!$configField) {
            return null;
        }

        $field = $configField->getField();

        $type = $field->getTypeInstance();
        $type->setValue($this->value);

        if (!empty($type->hasFiles())) {
            $scenario = TagerSettingsConfig::getFieldScenario($this->model->key);
            if (!empty($scenario)) {
                $type->applyFileScenario($scenario);
            }
        }

        $this->model->value = $type->getDatabaseValue();
        $this->model->changed = true;
        $this->model->save();

        $httpCache->clear('/tager/settings');

        event(new TagerSettingChanged($this->model->key));

        return $this->model;
    }
}
