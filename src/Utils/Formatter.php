<?php

namespace OZiTAG\Tager\Backend\Settings\Utils;

use Ozerich\FileStorage\Models\File;
use Ozerich\FileStorage\Repositories\FileRepository;
use OZiTAG\Tager\Backend\Utils\Enums\FieldType;

class Formatter
{
    public function formatValue($value, $type)
    {
        $fileRepository = new FileRepository(new File());

        switch ($type) {
            case FieldType::Number:
                return (int)$value;
            case FieldType::Image:
                return $fileRepository->find($value);
            case FieldType::Text:
            case FieldType::String:
                return is_null($value) ? '' : $value;
            default:
                return $value;
        }
    }
}
