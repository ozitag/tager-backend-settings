<?php

namespace OZiTAG\Tager\Backend\Settings\Console;

use Illuminate\Console\Command;
use Ozerich\FileStorage\Storage;
use OZiTAG\Tager\Backend\Fields\Enums\FieldType;
use OZiTAG\Tager\Backend\Fields\TypeFactory;
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

    public function handle(SettingsRepository $repository)
    {
        $settings = config()->get('tager-settings');
        if (!$settings) {
            return;
        }

        $exists = [];
        foreach ($repository->all() as $item) {
            $exists[$item->key] = false;
        }

        foreach ($settings as $ind => $setting) {
            if (!isset($setting['key'])) {
                continue;
            }

            $model = $repository->findOneByKey($setting['key']);
            if (!$model) {
                $model = $repository->createModelInstance();
                $model->key = trim($setting['key']);
                $model->changed = false;
            }

            $model->priority = $ind + 1;
            $model->public = isset($setting['private']) ? ($setting['private'] ? false : true) : true;
            $model->type = isset($setting['type']) && FieldType::hasValue($setting['type']) ? $setting['type'] : FieldType::Text;
            $model->label = isset($setting['label']) ? $setting['label'] : $setting['label'];

            $type = TypeFactory::create($model->type);
            $type->setValue(isset($setting['value']) ? $setting['value'] : null);

            if (!$model->changed) {
                $model->value = $type->getDatabaseValue();
            }

            if ($type->hasFiles()) {
                $scenario = TagerSettingsConfig::getFieldParam($model->key, 'scenario');
                if ($scenario) {
                    $type->applyFileScenario($scenario);
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
