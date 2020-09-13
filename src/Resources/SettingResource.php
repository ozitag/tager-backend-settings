<?php

namespace OZiTAG\Tager\Backend\Settings\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use OZiTAG\Tager\Backend\Fields\TypeFactory;

class SettingResource extends JsonResource
{
    private function prepareValue()
    {
        $type = TypeFactory::create($this->type);
        $type->loadValueFromDatabase($this->value);

        return $type->getAdminJson();
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
