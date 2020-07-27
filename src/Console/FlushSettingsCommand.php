<?php

namespace OZiTAG\Tager\Backend\Settings\Console;

use Illuminate\Console\Command;
use Ozerich\FileStorage\Storage;
use OZiTAG\Tager\Backend\Fields\Enums\FieldType;
use OZiTAG\Tager\Backend\Fields\FieldFactory;
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

            $model->type = isset($setting['type']) && FieldType::hasValue($setting['type']) ? $setting['type'] : FieldType::Text;
            $model->label = isset($setting['label']) ? $setting['label'] : $setting['label'];

            $field = FieldFactory::create($model->type);
            $field->setValue(isset($setting['value']) ? $setting['value'] : null);

            if (!$model->changed) {
                $model->value = $field->getDatabaseValue();
            }

            if ($field->hasFiles()) {
                $scenario = TagerSettingsConfig::getFieldParam($model->key, 'scenario');
                if ($scenario) {
                    $field->applyFileScenario($scenario);
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
