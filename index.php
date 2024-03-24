<?php



defined('PLUGINPATH') or exit('No direct script access allowed');



const ASAAS_WEBHOOK_ENDPOINT = 'asaas_payments';



/*

  Plugin Name: Asaas

  Description: It's a asaas plugin.

  Version: 1.0

  Requires at least: 3.0

  Author: Author Name

  Author URL: https://author_url.asaas

 */



//add menu item to left menu

app_hooks()->add_filter('app_filter_staff_left_menu', function ($sidebar_menu) {

    $sidebar_menu["asaas"] = array(

        "name" => "asaas",

        "url" => "asaas",

        "class" => "hash",

        "position" => 3,

    );



    return $sidebar_menu;

});



//add admin setting menu item

app_hooks()->add_filter('app_filter_admin_settings_menu', function ($settings_menu) {

    $settings_menu["plugins"][] = array("name" => "asaas", "url" => "asaas_settings");

    return $settings_menu;

});



//add webhook url to csrf exclude uris

app_hooks()->add_filter('app_filter_app_csrf_exclude_uris', function ($app_csrf_exclude_uris) {

    if (!in_array(ASAAS_WEBHOOK_ENDPOINT, $app_csrf_exclude_uris)) {

        array_push($app_csrf_exclude_uris, ASAAS_WEBHOOK_ENDPOINT);

    }



    return $app_csrf_exclude_uris;

});



//install dependencies

register_installation_hook("Asaas", function ($item_purchase_code) {

    /*

     * you can verify the item puchase code from here if you want. 

     * you'll get the inputted puchase code with $item_purchase_code variable

     * use exit(); here if there is anything doesn't meet it's requirements

     */



    $this_is_required = true;

    if (!$this_is_required) {

        echo json_encode(array("success" => false, "message" => "This is required!"));

        exit();

    }



    //run installation sql

    $db = db_connect('default');

    $dbprefix = get_db_prefix();



    $sql_query = "CREATE TABLE IF NOT EXISTS `" . $dbprefix . "asaas_settings` (

        `setting_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,

        `setting_value` mediumtext COLLATE utf8_unicode_ci NOT NULL,

        `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'app',

        `deleted` tinyint(1) NOT NULL DEFAULT '0',

        UNIQUE KEY `setting_name` (`setting_name`)

        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

    $db->query($sql_query);



    $sql_query = "INSERT INTO `" . $dbprefix . "asaas_settings` (`setting_name`, `setting_value`, `deleted`) VALUES 

                ('asaas_token', '', 0),

                ('asaas_payment_method', '', 0),

                ('asaas_payment_note', '', 0);";



    $db->query($sql_query);

});



//add setting link to the plugin setting

app_hooks()->add_filter('app_filter_action_links_of_Asaas', function () {

    $action_links_array = array(

        anchor(get_uri("asaas"), "Asaas"),

        anchor(get_uri("asaas_settings"), "Asaas settings"),

    );



    return $action_links_array;

});



//update plugin

register_update_hook("Asaas", function () {

    echo "Please follow this instructions to update:";

    echo "<br />";

    echo "Your logic to update...";

});



//uninstallation: remove data from database

register_uninstallation_hook("Asaas", function () {

    $dbprefix = get_db_prefix();

    $db = db_connect('default');



    $sql_query = "DROP TABLE IF EXISTS `" . $dbprefix . "asaas_settings`;";

    $db->query($sql_query);

});

