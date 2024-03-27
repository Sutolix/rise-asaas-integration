<?php

namespace Config;

$routes = Services::routes();

$routes->post(ASAAS_WEBHOOK_ENDPOINT, 'Asaas::handleWebhook', ['namespace' => 'Asaas\Controllers']);

$routes->get('asaas_settings', 'Asaas_settings::index', ['namespace' => 'Asaas\Controllers']);
$routes->get('asaas_settings/(:any)', 'Asaas_settings::$1', ['namespace' => 'Asaas\Controllers']);
$routes->post('asaas_settings/(:any)', 'Asaas_settings::$1', ['namespace' => 'Asaas\Controllers']);

