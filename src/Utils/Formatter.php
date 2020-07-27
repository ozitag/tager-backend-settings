<?php

namespace OZiTAG\Tager\Backend\Settings\Utils;

use Ozerich\FileStorage\Models\File;
use Ozerich\FileStorage\Repositories\FileRepository;
use OZiTAG\Tager\Backend\Core\Enums\Enum\FieldType;

class Formatter
{
    public function formatValue($value, $type)
    {
        $fileRepository = new FileRepository(new File());

        switch ($type) {
            case SettingType::NUMBER:
                return (int)$value;
            case SettingType::IMAGE:
                return $fileRepository->find($value);
            case SettingType::TEXT:
            case SettingType::STRING:
                return is_null($value) ? '' : $value;
            default:
                return $value;
        }
    }
}
