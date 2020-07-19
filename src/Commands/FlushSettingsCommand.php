<?php

namespace OZiTAG\Tager\Backend\Settings\Commands;

use Illuminate\Console\Command;
use Ozerich\FileStorage\Storage;
use OZiTAG\Tager\Backend\Seo\Models\SeoPage;
use OZiTAG\Tager\Backend\Seo\Repositories\SeoPageRepository;
use OZiTAG\Tager\Backend\Settings\Enums\SettingType;
use OZiTAG\Tager\Backend\Settings\Repositories\SettingsRepository;
use OZiTAG\Tager\Backend\Settings\TagerSettingsConfig;

class FlushSettingsCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'tager:settings-flush';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync DB settings with config';

    public function handle(SettingsRepository $repository, Storage $fileStorage)
    {
        $settings = config()->get('tager-settings');
        if (!$settings) {
            return;
        }

        $exists = [];
        foreach ($repository->all() as $item) {
            $exists[$item->key] = false;
        }

        foreach ($settings as $setting) {
            if (!isset($setting['key'])) {
                continue;
            }

            $model = $repository->findOneByKey($setting['key']);
            if (!$model) {
                $model = $repository->createModelInstance();
                $model->key = trim($setting['key']);
                $model->changed = false;
            }

            $model->type = isset($setting['type']) && SettingType::hasKey($setting['type']) ? $setting['type'] : SettingType::TEXT;
            $model->label = isset($setting['label']) ? $setting['label'] : $setting['label'];

            if (!$model->changed) {
                $model->value = isset($setting['value']) ? $setting['value'] : null;
            }

            if ($model->value && $model->type == SettingType::IMAGE) {
                $scenario = TagerSettingsConfig::getFieldParam($model->key, 'scenario');
                if ($scenario) {
                    $fileStorage->setFileScenario($model->value, $scenario);
                }
            }

            $exists[$model->key] = true;

            $model->save();
        }

        foreach ($exists as $key => $value) {
            if (!$value) {
                $repository->deleteByKey($key);
            }
        }
    }
}
