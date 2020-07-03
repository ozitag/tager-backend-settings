<?php

namespace OZiTAG\Tager\Backend\Settings\Repositories;

use App\Models\Order;
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
        return TagerSettings::query()->where('key', '=', $key)->first();
    }
}
