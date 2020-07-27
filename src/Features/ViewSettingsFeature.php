<?php

namespace OZiTAG\Tager\Backend\Settings\Features;

use Illuminate\Http\Resources\Json\JsonResource;
use Ozerich\FileStorage\Models\File;
use Ozerich\FileStorage\Repositories\FileRepository;
use OZiTAG\Tager\Backend\Core\Features\Feature;
use OZiTAG\Tager\Backend\Fields\FieldFactory;
use OZiTAG\Tager\Backend\Settings\Repositories\SettingsRepository;

class ViewSettingsFeature extends Feature
{
    public function handle(SettingsRepository $repository)
    {
        $items = $repository->all();

        $result = [];
        foreach ($items as $item) {
            $field = FieldFactory::create($item->type);
            $field->setValue($item->value);

            $result[] = [
                'key' => $item->key,
                'value' => $field->getPublicValue()
            ];
        }

        return new JsonResource($result);
    }
}
