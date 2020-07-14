<?php

namespace OZiTAG\Tager\Backend\Settings\Jobs;

use Illuminate\Http\Exceptions\HttpResponseException;
use Ozerich\FileStorage\Repositories\IFileRepository;
use OZiTAG\Tager\Backend\Settings\Enums\SettingType;
use OZiTAG\Tager\Backend\Settings\Models\TagerSettings;
use OZiTAG\Tager\Backend\Settings\Repositories\SettingsRepository;

class UpdateSettingValueJob
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

    private function checkImageValue(IFileRepository $fileRepository)
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

        return $this->value;
    }

    private function checkNumberValue()
    {
        if (!is_numeric($this->value)) {
            $this->responseError('INVALID_FORMAT', 'Should be a number');
        }

        return (int)$this->value;
    }

    public function handle(IFileRepository $repository)
    {
        if ($this->model->type == SettingType::STRING || $this->model->type == SettingType::TEXT) {
            $this->model->value = (string)$this->value;
        } else if ($this->model->type == SettingType::NUMBER) {
            $this->model->value = $this->checkNumberValue();
        } else if ($this->model->type == SettingType::IMAGE) {
            $this->model->value = $this->checkImageValue($repository);
        }

        $this->model->changed = true;
        $this->model->save();

        return $this->model;
    }
}
