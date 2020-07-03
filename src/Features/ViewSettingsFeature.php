<?php

namespace OZiTAG\Tager\Backend\Settings\Features;

use Illuminate\Http\Resources\Json\JsonResource;
use OZiTAG\Tager\Backend\Core\Feature;
use OZiTAG\Tager\Backend\Settings\Repositories\SettingsRepository;

class ViewSettingFeature extends Feature
{
    private $key;

    public function __construct($key)
    {
        $this->key = $key;
    }

    public function handle(SettingsRepository $repository)
    {
        $items = $repository->all();

        $result = [];
        foreach($items as $item){
            $result[$item->key] = $item->value;
        }

        return new JsonResource($result);
    }
}
