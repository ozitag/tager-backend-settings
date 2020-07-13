<?php

namespace OZiTAG\Tager\Backend\Settings\Utils;

use Ozerich\FileStorage\Models\File;
use Ozerich\FileStorage\Repositories\FileRepository;
use OZiTAG\Tager\Backend\Settings\Enums\SettingType;

class Formatter
{
    public function formatValue($value, $type)
    {
        $fileRepository = new FileRepository(new File());

        switch ($type) {
            case SettingType::NUMBER:
                return (int)$value;
            case SettingType::IMAGE:
                $model = $fileRepository->find($value);
                return $model ? $model->getUrl() : null;
            case SettingType::TEXT:
            case SettingType::STRING:
                return is_null($value) ? '' : $value;
            default:
                return $value;
        }
    }
}
