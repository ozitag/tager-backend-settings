<?php

namespace OZiTAG\Tager\Backend\Settings\Features\Admin;

use Illuminate\Http\Request;
use OZiTAG\Tager\Backend\Core\Features\Feature;
use OZiTAG\Tager\Backend\Settings\Jobs\UpdateSettingValueJob;
use OZiTAG\Tager\Backend\Settings\Repositories\SettingsRepository;
use OZiTAG\Tager\Backend\Settings\Resources\SettingFullResource;
use OZiTAG\Tager\Backend\Settings\Resources\SettingResource;

class UpdateSettingsFeature extends Feature
{
    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function handle(Request $request, SettingsRepository $repository)
    {
        $model = $repository->find($this->id);
        if (!$model) {
            abort(404, 'Setting not found');
        }

        $value = $request->get('value');
        if (!$value) {
            $value = null;
        }

        $model = $this->run(UpdateSettingValueJob::class, [
            'model' => $model,
            'value' => $value
        ]);

        return new SettingFullResource($model);
    }
}
