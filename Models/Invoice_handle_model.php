<?php

namespace Asaas\Models;

use App\Models\Crud_model;
use App\Models\Invoices_model;

class Invoice_handle_model extends Crud_model
{
    protected $table = null;

    function __construct()
    {
        $this->table = 'invoice_items';
        parent::__construct($this->table);
    }

    function get_invoice_by_asaas_invoice_number($invoice_number)
    {
        $custom_fields_table = $this->db->prefixTable('custom_field_values');
        $invoices_table = $this->db->prefixTable('invoices');

        $sql = "SELECT $invoices_table.*
        FROM $invoices_table
        JOIN $custom_fields_table ON $custom_fields_table.related_to_id = $invoices_table.id
        WHERE $custom_fields_table.value = $invoice_number";
        
        $result = $this->db->query($sql)->getResult();
        $invoice = !empty($result) ? $result[0] : [];

        return $invoice;
    }
}
