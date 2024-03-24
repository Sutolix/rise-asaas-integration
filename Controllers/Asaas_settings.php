<?php



namespace Asaas\Controllers;



use App\Controllers\Security_Controller;



class Asaas_settings extends Security_Controller {



    protected $Asaas_settings_model;


    function __construct() {

        parent::__construct();

        $this->access_only_admin_or_settings_admin();

        $this->Asaas_settings_model = new \Asaas\Models\Asaas_settings_model();

    }



    function index() {

        $payments_methods = $this->Payment_methods_model->get_details()->getResult();

        $payment_methods_options = ['' => 'Selecione'];
        foreach ($payments_methods as $method) {
            $payment_methods_options[$method->id] = $method->title;
        }

        $data['payment_methods_options'] = $payment_methods_options;

        return $this->template->rander("Asaas\Views\settings\index", $data);

    }



    function save() {

        $this->Asaas_settings_model->save_setting("asaas_token", $this->request->getPost("asaas_token"));
        $this->Asaas_settings_model->save_setting("asaas_payment_method", $this->request->getPost("asaas_payment_method"));
        $this->Asaas_settings_model->save_setting("asaas_payment_note", $this->request->getPost("asaas_payment_note"));

        echo json_encode(array("success" => true, 'message' => app_lang('settings_updated')));

    }

    

}

