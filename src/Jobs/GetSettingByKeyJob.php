<?php

namespace OZiTAG\Tager\Backend\Settings\Jobs;

use OZiTAG\Tager\Backend\Core\Jobs\Job;
use OZiTAG\Tager\Backend\Settings\Repositories\SettingsRepository;

class GetSettingByKeyJob extends Job
{
    /** @var string */
    private $key;

    public function __construct($key)
    {
        $this->key = $key;
    }

    public function handle(SettingsRepository $repository)
    {
        return $repository->findOneByKey($this->key);
    }
}
