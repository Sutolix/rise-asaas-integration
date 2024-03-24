<?php



namespace Asaas\Controllers;



use App\Controllers\App_Controller;

use App\Models\Invoice_payments_model;

use App\Models\Invoices_model;

use Asaas\Models\Invoice_handle_model;



class Asaas extends App_Controller

{



    const READABLE_EVENTS = [

        'PAYMENT_RECEIVED'  => 'fully_paid',

        'PAYMENT_CONFIRMED' => 'fully_paid',

        'PAYMENT_OVERDUE'   => 'not_paid',

        'PAYMENT_REFUNDED'  => 'not_paid',

        'PAYMENT_DELETED '  => 'not_paid'

    ];



    function index()

    {

        return redirect()->to('/');

    }



    function handleWebhook()

    {

        $asaas_token = get_asaas_setting('asaas_token');

        $authToken = $this->request->getHeaderLine('asaas-access-token');



        if ($authToken !== $asaas_token) {

            return $this->response->setJSON([

                'status' => 'error',

                'message' => 'Unauthorized.'

            ]);

        }



        $data = $this->request->getJSON();

        $event = isset($data->event) && !empty($data->event) ? $data->event : '';

        $payment = isset($data->payment) && !empty($data->payment) ? $data->payment : '';

        

        if(empty($event) || empty($payment)) {

            return $this->response->setJSON([

                'status' => 'error',

                'message' => 'Bad request: Essential infos are missing'

            ]);

        }



        if(!isset(self::READABLE_EVENTS[$event])) {

            return $this->response->setJSON([

                'status' => 'error',

                'message' => 'Unhandled event.'

            ]);

        }



        $invoiceID = $this->updateInvoice($event, $payment);



        if(!$invoiceID) {

            return $this->response->setJSON([

                    'status'    => 'fail',

                    'message'   => 'Invoice update failure.',

                ]

            );

        }



        return $this->response->setJSON(

            [

                'status'        => 'success',

                'message'       => 'Updated invoice.',

                'invoiceID'     => $invoiceID

            ]

        );

    }



    function updateInvoice($event, $payment) {

        try {

            $invoiceNumber = $payment->invoiceNumber;



            $Invoices_handle_model = new Invoice_handle_model();

            $invoice = $Invoices_handle_model->get_invoice_by_asaas_invoice_number($invoiceNumber);



            if(!empty($invoice)) {

                $status = self::READABLE_EVENTS[$event];

                $Invoice_payments_model = new Invoice_payments_model();



                if($status === 'fully_paid') {

                    $payment_method_id = get_asaas_setting('asaas_payment_method');
                    $note = get_asaas_setting('asaas_payment_note');

                    $invoice_payment_data = [

                        "invoice_id"        => $invoice->id,

                        "payment_date"      => get_current_utc_time(),

                        "payment_method_id" => $payment_method_id,

                        "note"              => $note,

                        "amount"            => $payment->value,

                        "transaction_id"    => $payment->id,

                        "created_at"        => get_current_utc_time(),

                        "created_by"        => $invoice->client_id,

                        

                    ];



                    $existing = $Invoice_payments_model->get_one_where(array("transaction_id" => $payment->id, "deleted" => 0));

                    if (!$existing->id) {

                        $Invoice_payments_model->ci_save($invoice_payment_data);

                    };

                }



                if($status === 'not_paid') {

                    $existing = $Invoice_payments_model->get_one_where(array("transaction_id" => $payment->id, "deleted" => 0));

                    

                    if ($existing->id) {

                        $Invoice_payments_model->delete($existing->id);

                    }

                }

                

                $Invoices_model = new Invoices_model();

                $Invoices_model->update_invoice_status($invoice->id, $status);



                return $invoice->id;

            }



        } catch (\Throwable $th) {

            // var_dump($th);

            return false;

        }

    }

}

