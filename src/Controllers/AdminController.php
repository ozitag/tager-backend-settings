<?php

namespace OZiTAG\Tager\Backend\Settings\Controllers;

use OZiTAG\Tager\Backend\Core\Controllers\Controller;
use OZiTAG\Tager\Backend\Settings\Features\Admin\ListSettingsFeature;
use OZiTAG\Tager\Backend\Settings\Features\Admin\UpdateSettingsFeature;
use OZiTAG\Tager\Backend\Settings\Features\Admin\ViewSettingsFeature;

class AdminController extends Controller
{
    public function index()
    {
        return $this->serve(ListSettingsFeature::class);
    }

    public function view($id)
    {
        return $this->serve(ViewSettingsFeature::class, ['id' => $id]);
    }

    public function update($id)
    {
        return $this->serve(UpdateSettingsFeature::class, ['id' => $id]);
    }
}
