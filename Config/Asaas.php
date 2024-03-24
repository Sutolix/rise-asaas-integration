<?php

namespace Asaas\Config;

use CodeIgniter\Config\BaseConfig;
use Asaas\Models\Asaas_settings_model;

class Asaas extends BaseConfig {

    public $app_settings_array = array(
        "asaas_file_path" => PLUGIN_URL_PATH . "Asaas/files/asaas_files/"
    );

    public function __construct() {
        $asaas_settings_model = new Asaas_settings_model();

        $settings = $asaas_settings_model->get_all_settings()->getResult();
        foreach ($settings as $setting) {
            $this->app_settings_array[$setting->setting_name] = $setting->setting_value;
        }
    }

}
