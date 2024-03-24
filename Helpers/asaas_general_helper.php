<?php

helper('array');

/**
 * get the defined config value by a key
 * @param string $key
 * @return config value
 */
if (!function_exists('get_asaas_setting')) {

    function get_asaas_setting($key = "") {
        $config = new Asaas\Config\Asaas();

        $setting_value = isset($config->app_settings_array[$key])
            ? $config->app_settings_array[$key]
            : NULL;

        if ($setting_value !== NULL) {
            return $setting_value;
        } else {
            return "";
        }
    }

}

/**
 * link the css files 
 * 
 * @param array $array
 * @return print css links
 */
if (!function_exists('asaas_load_css')) {

    function asaas_load_css(array $array) {
        $version = get_setting("app_version");

        foreach ($array as $uri) {
            echo "<link rel='stylesheet' type='text/css' href='" . base_url(PLUGIN_URL_PATH . "Asaas/$uri") . "?v=$version' />";
        }
    }

}

if (!function_exists('asaas_get_source_url')) {

    function asaas_get_source_url($asaas_file = "") {
        if (!$asaas_file) {
            return "";
        }

        try {
            $file = unserialize($asaas_file);
            if (is_array($file)) {
                return get_source_url_of_file($file, get_asaas_setting("asaas_file_path"), "thumbnail", false, false, true);
            }
        } catch (\Exception $ex) {
            
        }
    }

}