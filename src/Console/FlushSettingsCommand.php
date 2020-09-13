<?php

namespace OZiTAG\Tager\Backend\Settings\Console;

use Illuminate\Console\Command;
use Ozerich\FileStorage\Storage;
use OZiTAG\Tager\Backend\Fields\Enums\FieldType;
use OZiTAG\Tager\Backend\Fields\TypeFactory;
use OZiTAG\Tager\Backend\Settings\Repositories\SettingsRepository;
use OZiTAG\Tager\Backend\Settings\Utils\TagerSettingsConfig;

class FlushSettingsCommand extends \OZiTAG\Tager\Backend\Core\Console\Command
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

    private $repository;

    public function __construct(SettingsRepository $repository)
    {
        $this->repository = $repository;

        parent::__construct();
    }

    public function handle()
    {
        $this->log('Start');

        $repository = $this->repository;

        $exists = [];
        foreach ($repository->all() as $item) {
            $exists[$item->key] = false;
        }

        $settings = TagerSettingsConfig::getFields();

        $ind = 0;
        foreach ($settings as $key => $setting) {

            $this->log($key . ': ', false);

            $model = $repository->findOneByKey($key);
            if (!$model) {
                $model = $repository->createModelInstance();
                $model->key = trim($key);
                $model->changed = false;

                $isNew = true;
            } else {
                $isNew = false;
            }

            $model->priority = ++$ind;
            $model->public = $setting->isPrivate() == false;
            $model->type = $setting->getField()->getType();
            $model->label = $setting->getField()->getLabel();

            $type = TypeFactory::create($model->type);
            $type->setValue($setting->getValue());

            if (!$model->changed) {
                $model->value = $type->getDatabaseValue();
            }

            if ($type->hasFiles()) {
                $scenario = TagerSettingsConfig::getFieldScenario($model->key);
                if ($scenario) {
                    $type->applyFileScenario($scenario);
                }
            }

            $exists[$model->key] = true;

            $model->save();

            if ($isNew) {
                $this->log('Created ID ' . $model->id);
            } else {
                $this->log('Updated ID ' . $model->id);
            }
        }

        foreach ($exists as $key => $value) {
            if (!$value) {
                $repository->deleteByKey($key);

                $this->log('Delete "' . $key . '"');
            }
        }

        $this->log('End');
    }
}
