<?php

namespace OZiTAG\Tager\Backend\Settings\Features;

use Illuminate\Http\Resources\Json\JsonResource;
use OZiTAG\Tager\Backend\Core\Features\Feature;
use OZiTAG\Tager\Backend\Fields\TypeFactory;
use OZiTAG\Tager\Backend\Settings\Repositories\SettingsRepository;

class ViewSettingsFeature extends Feature
{
    public function handle(SettingsRepository $repository)
    {
        $items = $repository->getPublic();

        $result = [];
        foreach ($items as $item) {
            $type = TypeFactory::create($item->type);
            $type->loadValueFromDatabase($item->value);

            $result[] = [
                'key' => $item->key,
                'value' => $type->getPublicValue()
            ];
        }

        return new JsonResource($result);
    }
}
