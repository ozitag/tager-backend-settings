<?php

namespace OZiTAG\Tager\Backend\Settings\Features;

use Illuminate\Http\Resources\Json\JsonResource;
use OZiTAG\Tager\Backend\Core\Feature;
use OZiTAG\Tager\Backend\Settings\Repositories\SettingsRepository;
use OZiTAG\Tager\Backend\Settings\Utils\Formatter;

class ViewSettingsFeature extends Feature
{
    public function handle(SettingsRepository $repository, Formatter $formatter)
    {
        $items = $repository->all();

        $result = [];
        foreach ($items as $item) {
            $result[$item->key] = $formatter->formatValue($item->value, $item->type);
        }

        return new JsonResource($result);
    }
}
