<?php

namespace OZiTAG\Tager\Backend\Settings\Features\Admin;

use Illuminate\Http\Resources\Json\JsonResource;
use OZiTAG\Tager\Backend\Core\Feature;
use OZiTAG\Tager\Backend\Mail\Resources\MailLogResource;
use OZiTAG\Tager\Backend\Settings\Repositories\SettingsRepository;
use OZiTAG\Tager\Backend\Settings\Resources\SettingResource;

class ListSettingsFeature extends Feature
{
    public function handle(SettingsRepository $repository)
    {
        return SettingResource::collection($repository->all());
    }
}
