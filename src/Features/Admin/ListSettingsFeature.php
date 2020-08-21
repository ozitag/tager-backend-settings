<?php

namespace OZiTAG\Tager\Backend\Settings\Features\Admin;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;
use OZiTAG\Tager\Backend\Core\Features\Feature;
use OZiTAG\Tager\Backend\Settings\Repositories\SettingsRepository;
use OZiTAG\Tager\Backend\Settings\Resources\SettingResource;
use OZiTAG\Tager\Backend\Settings\Utils\TagerSettingsConfig;

class ListSettingsFeature extends Feature
{
    /** @var SettingsRepository */
    private $settingsRepository;

    public function __construct()
    {
        $this->settingsRepository = App::make(SettingsRepository::class);
    }

    private function getField($field)
    {
        $model = $this->settingsRepository->findOneByKey($field['key']);

        if (!$model) {
            return null;
        }

        return new SettingResource($model);
    }

    private function getFields($fields)
    {
        $result = [];

        foreach ($fields as $field) {
            $model = $this->getField($field);
            if ($model) {
                $result[] = $model;
            }
        }

        return $result;
    }

    public function handle(SettingsRepository $repository)
    {
        $result = [];

        if (TagerSettingsConfig::hasSections()) {

            foreach (TagerSettingsConfig::getSections() as $section) {
                $result[] = [
                    'name' => $section,
                    'fields' => $this->getFields(TagerSettingsConfig::getFields($section))
                ];
            }
        } else {
            $result[] = [
                'name' => '',
                'fields' => $this->getFields(TagerSettingsConfig::getFields())
            ];
        }

        return new JsonResource($result);

        return SettingResource::collection($repository->all());
    }
}
