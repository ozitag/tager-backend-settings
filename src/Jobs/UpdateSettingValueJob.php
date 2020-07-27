<?php

namespace OZiTAG\Tager\Backend\Settings\Jobs;

use Illuminate\Http\Exceptions\HttpResponseException;
use Ozerich\FileStorage\Repositories\IFileRepository;
use Ozerich\FileStorage\Storage;
use OZiTAG\Tager\Backend\Core\Jobs\Job;
use OZiTAG\Tager\Backend\Fields\FieldFactory;
use OZiTAG\Tager\Backend\HttpCache\HttpCache;
use OZiTAG\Tager\Backend\Settings\Models\TagerSettings;
use OZiTAG\Tager\Backend\Settings\TagerSettingsConfig;

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

    private function processFiles()
    {
        $scenario = TagerSettingsConfig::getFieldParam($this->model->key, 'scenario');
        if (!empty($scenario)) {
            $fileStorage->setFileScenario($imageId, $scenario);
        }
    }

    public function handle(IFileRepository $repository, Storage $fileStorage, HttpCache $httpCache)
    {
        $field = FieldFactory::create($this->model->type);
        $field->setValue($this->value);

        if (!empty($field->hasFiles())) {
            $scenario = TagerSettingsConfig::getFieldParam($this->model->key, 'scenario');
            if (!empty($scenario)) {
                $field->applyFileScenario($scenario);
            }
        }

        $this->model->value = $field->getDatabaseValue();
        $this->model->changed = true;
        $this->model->save();

        $httpCache->clear('/tager/settings');

        return $this->model;
    }
}
