<?php

namespace OZiTAG\Tager\Backend\Settings\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use OZiTAG\Tager\Backend\Fields\Enums\FieldType;
use OZiTAG\Tager\Backend\Fields\TypeFactory;
use OZiTAG\Tager\Backend\Settings\Utils\TagerSettingsConfig;

class SettingResource extends JsonResource
{
    private function prepareValue()
    {
        $type = TypeFactory::create(FieldType::from($this->type));
        $type->loadValueFromDatabase($this->value);

        return $type->getAdminJson();
    }

    private function prepareField()
    {
        $field = TagerSettingsConfig::getField($this->key);
        if (!$field) {
            return null;
        }

        $field->getField()->setName($this->key);

        return $field->getField()->getJson();
    }

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'config' => $this->prepareField(),
            'value' => $this->prepareValue()
        ];
    }
}
