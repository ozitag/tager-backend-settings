<?php

namespace OZiTAG\Tager\Backend\Settings\Repositories;

use OZiTAG\Tager\Backend\Core\Repositories\EloquentRepository;
use OZiTAG\Tager\Backend\Settings\Models\TagerSettings;

class SettingsRepository extends EloquentRepository
{
    public function __construct(TagerSettings $model)
    {
        parent::__construct($model);
    }

    public function findOneByKey(string $key)
    {
        return $this->model->query()->where('key', '=', $key)->first();
    }

    public function getPublic()
    {
        return $this->model->where('public', '=', true)->get();
    }

    public function deleteByKey(string $key)
    {
        $this->model->query()->where('key', '=', $key)->delete();
    }
}
