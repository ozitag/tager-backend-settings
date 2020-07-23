<?php

namespace OZiTAG\Tager\Backend\Settings\Features\Admin;

use OZiTAG\Tager\Backend\Core\Features\Feature;
use OZiTAG\Tager\Backend\Settings\Repositories\SettingsRepository;
use OZiTAG\Tager\Backend\Settings\Resources\SettingFullResource;

class ViewSettingsFeature extends Feature
{
    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function handle(SettingsRepository $repository)
    {
        $model = $repository->find($this->id);
        if (!$model) {
            abort(404, 'Setting not found');
        }

        return new SettingFullResource($model);
    }
}
