<?php

namespace OZiTAG\Tager\Backend\Settings\Jobs;

use Illuminate\Http\Exceptions\HttpResponseException;
use Ozerich\FileStorage\Repositories\IFileRepository;
use Ozerich\FileStorage\Storage;
use OZiTAG\Tager\Backend\Core\Jobs\Job;
use OZiTAG\Tager\Backend\Utils\Enums\FieldType;
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

    private function checkImageValue(IFileRepository $fileRepository, Storage $fileStorage)
    {
        if (!$this->value) {
            return null;
        }

        if (!is_numeric($this->value)) {
            $this->responseError('INVALID_FORMAT', 'Should be a number');
        }

        $model = $fileRepository->find($this->value);
        if (!$model) {
            $this->responseError('INVALID_VALUE', 'File ID ' . $this->value . ' not found');
        }

        $scenario = TagerSettingsConfig::getFieldParam($this->model->key, 'scenario');
        if (!empty($scenario)) {
            $fileStorage->setFileScenario($this->value, $scenario);
        }

        return $this->value;
    }

    private function checkNumberValue()
    {
        if (!is_numeric($this->value)) {
            $this->responseError('INVALID_FORMAT', 'Should be a number');
        }

        return (int)$this->value;
    }

    public function handle(IFileRepository $repository, Storage $fileStorage)
    {
        if ($this->model->type == FieldType::String || $this->model->type == FieldType::Text) {
            $this->model->value = (string)$this->value;
        } else if ($this->model->type == FieldType::Number) {
            $this->model->value = $this->checkNumberValue();
        } else if ($this->model->type == FieldType::Image) {
            $this->model->value = $this->checkImageValue($repository, $fileStorage);
        }

        $this->model->changed = true;
        $this->model->save();

        return $this->model;
    }
}
