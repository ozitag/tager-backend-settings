<?php

namespace OZiTAG\Tager\Backend\Settings\Jobs;

use Illuminate\Http\Exceptions\HttpResponseException;
use OZiTAG\Tager\Backend\Core\Jobs\Job;
use OZiTAG\Tager\Backend\Fields\TypeFactory;
use OZiTAG\Tager\Backend\HttpCache\HttpCache;
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
        $type = TypeFactory::create($this->model->type);
        $type->setValue($this->value);

        if (!empty($type->hasFiles())) {
            $scenario = TagerSettingsConfig::getFieldParam($this->model->key, 'scenario');
            if (!empty($scenario)) {
                $type->applyFileScenario($scenario);
            }
        }

        $this->model->value = $type->getDatabaseValue();
        $this->model->changed = true;
        $this->model->save();

        $httpCache->clear('/tager/settings');

        return $this->model;
    }
}
