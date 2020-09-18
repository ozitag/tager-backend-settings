<?php

namespace OZiTAG\Tager\Backend\Settings\Repositories;

use Illuminate\Support\Facades\DB;
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
        return $this->model->query()->where(DB::raw('BINARY `key`'), '=', $key)->first();
    }

    public function getPublic()
    {
        return $this->model->where('public', '=', true)->get();
    }

    public function deleteByKey(string $key)
    {
        $this->model->query()->where(DB::raw('BINARY `key`'), '=', $key)->delete();
    }
}
