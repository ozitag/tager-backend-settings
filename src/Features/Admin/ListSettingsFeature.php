<?php

namespace OZiTAG\Tager\Backend\Settings\Features\Admin;

use OZiTAG\Tager\Backend\Core\Features\Feature;
use OZiTAG\Tager\Backend\Settings\Repositories\SettingsRepository;
use OZiTAG\Tager\Backend\Settings\Resources\SettingResource;

class ListSettingsFeature extends Feature
{
    public function handle(SettingsRepository $repository)
    {
        return SettingResource::collection($repository->all());
    }
}
