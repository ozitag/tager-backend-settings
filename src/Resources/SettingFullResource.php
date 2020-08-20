<?php

namespace OZiTAG\Tager\Backend\Settings\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use OZiTAG\Tager\Backend\Fields\TypeFactory;

class SettingFullResource extends JsonResource
{
    private function prepareValue()
    {
        $type = TypeFactory::create($this->type);
        $type->setValue($this->value);

        return $type->getAdminFullJson();
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
