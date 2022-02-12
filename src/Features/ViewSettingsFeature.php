<?php

namespace OZiTAG\Tager\Backend\Settings\Features;

use Illuminate\Http\Resources\Json\JsonResource;
use OZiTAG\Tager\Backend\Core\Features\Feature;
use OZiTAG\Tager\Backend\Fields\Enums\FieldType;
use OZiTAG\Tager\Backend\Fields\Fields\RepeaterField;
use OZiTAG\Tager\Backend\Fields\TypeFactory;
use OZiTAG\Tager\Backend\Fields\Types\RepeaterType;
use OZiTAG\Tager\Backend\Settings\Repositories\SettingsRepository;
use OZiTAG\Tager\Backend\Settings\Utils\TagerSettingsConfig;

class ViewSettingsFeature extends Feature
{
    public function handle(SettingsRepository $repository)
    {
        $items = $repository->getPublic();

        $result = [];
        foreach ($items as $item) {
            $type = TypeFactory::create(FieldType::from($item->type));

            if ($type instanceof RepeaterType) {
                $configField = TagerSettingsConfig::getField($item->key);
                if (!$configField) continue;

                /** @var RepeaterField $field */
                $field = $configField->getField();

                $type->setFields($field->getFields());
            }

            $type->loadValueFromDatabase($item->value);

            $result[] = [
                'key' => $item->key,
                'value' => $type->getPublicValue()
            ];
        }

        return new JsonResource($result);
    }
}
