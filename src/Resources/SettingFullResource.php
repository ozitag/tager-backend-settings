<?php

namespace OZiTAG\Tager\Backend\Settings\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Ozerich\FileStorage\Models\File;
use Ozerich\FileStorage\Repositories\FileRepository;
use OZiTAG\Tager\Backend\Fields\FieldFactory;

class SettingFullResource extends JsonResource
{
    private function prepareValue()
    {
        $field = FieldFactory::create($this->type);
        $field->setValue($this->value);

        return $field->getAdminFullJson();
    }

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'key' => $this->key,
            'type' => $this->type,
            'label' => $this->label,
            'value' => $this->prepareValue()
        ];
    }
}
