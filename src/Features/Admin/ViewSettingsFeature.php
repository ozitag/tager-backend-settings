<?php

namespace OZiTAG\Tager\Backend\Settings\Features\Admin;

use Illuminate\Http\Resources\Json\JsonResource;
use OZiTAG\Tager\Backend\Core\Feature;
use OZiTAG\Tager\Backend\Settings\Repositories\SettingsRepository;
use OZiTAG\Tager\Backend\Settings\Resources\SettingResource;

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

        return new SettingResource($model);
    }
}
