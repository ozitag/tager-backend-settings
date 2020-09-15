<?php

namespace OZiTAG\Tager\Backend\Settings\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use OZiTAG\Tager\Backend\Settings\Utils\TagerSettingsConfig;

class SettingFullResource extends JsonResource
{
    private function prepareValue()
    {
        $settingsField = TagerSettingsConfig::getField($this->key);
        if (!$settingsField) {
            return null;
        }

        $field = $settingsField->getField();

        $type = $field->getTypeInstance();
        $type->loadValueFromDatabase($this->value);

        return $type->getAdminFullJson();
    }

    private function prepareField()
    {
        $field = TagerSettingsConfig::getField($this->key);
        if (!$field) {
            return null;
        }

        return array_merge(
            ['name' => $this->key],
            $field->getField()->getJson()
        );
    }

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'template' => $this->prepareField(),
            'value' => $this->prepareValue(),
        ];
    }
}
