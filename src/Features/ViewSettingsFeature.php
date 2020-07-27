<?php

namespace OZiTAG\Tager\Backend\Settings\Features;

use Illuminate\Http\Resources\Json\JsonResource;
use OZiTAG\Tager\Backend\Core\Features\Feature;
use OZiTAG\Tager\Backend\Utils\Enums\FieldType;
use OZiTAG\Tager\Backend\Settings\Repositories\SettingsRepository;
use OZiTAG\Tager\Backend\Settings\Utils\Formatter;

class ViewSettingsFeature extends Feature
{
    public function handle(SettingsRepository $repository, Formatter $formatter)
    {
        $items = $repository->all();

        $result = [];
        foreach ($items as $item) {
            $value = $formatter->formatValue($item->value, $item->type);;
            if (($item->type === FieldType::Image || $item->type === FieldType::File) && $value) {
                $value = $value->getFullJson();
            }

            $result[] = [
                'key' => $item->key,
                'value' => $value,
            ];
        }

        return new JsonResource($result);
    }
}
