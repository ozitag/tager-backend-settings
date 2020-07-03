<?php

namespace OZiTAG\Tager\Backend\Settings\Commands;

use Illuminate\Console\Command;
use OZiTAG\Tager\Backend\Seo\Models\SeoPage;
use OZiTAG\Tager\Backend\Seo\Repositories\SeoPageRepository;
use OZiTAG\Tager\Backend\Settings\Enums\SettingType;
use OZiTAG\Tager\Backend\Settings\Repositories\SettingsRepository;

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

    public function handle(SettingsRepository $repository)
    {
        $settings = config()->get('tager-settings');
        if (!$settings) {
            return;
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

            $model->save();
        }
    }
}
