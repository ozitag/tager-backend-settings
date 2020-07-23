<?php

namespace OZiTAG\Tager\Backend\Settings\Controllers;

use OZiTAG\Tager\Backend\Core\Controllers\Controller;
use OZiTAG\Tager\Backend\Settings\Features\ViewSettingsFeature;

class PublicController extends Controller
{
    public function view()
    {
        return $this->serve(ViewSettingsFeature::class);
    }
}
