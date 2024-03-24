<?php asaas_load_css(array("assets/css/asaas_styles.css")); ?>

<?php

?>

<div id="page-content" class="page-wrapper clearfix">
    <div class="row">
        <div class="col-sm-3 col-lg-2">
            <?php
            $tab_view['active_tab'] = "asaas";
            echo view("settings/tabs", $tab_view);
            ?>
        </div>

        <div class="col-sm-9 col-lg-10">
            <div class="card">

                <div class="card-header">
                    <h4>Configurações da integração</h4>
                </div>

                <?php echo form_open(get_uri("asaas_settings/save"), array("id" => "asaas-settings-form", "class" => "general-form dashed-row", "role" => "form")); ?>

                <div class="card-body post-dropzone">
                    <div class="form-group">
                        <div class="row">
                            <label for="asaas_token" class=" col-md-3">authToken: </label>
                            <div class=" col-md-9">
                                <?php
                                echo form_input(array(
                                    "id" => "asaas_token",
                                    "name" => "asaas_token",
                                    "value" => get_asaas_setting("asaas_token"),
                                    "class" => "form-control",
                                    "placeholder" => "Token de autenticação do webhook do Asaas"
                                ));
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label for="asaas_payment_method" class=" col-md-3">Método de pagamento associado: </label>
                            <div class=" col-md-9">
                                <?php
                                echo form_dropdown(array(
                                    "id"        => "asaas_payment_method",
                                    "name"      => "asaas_payment_method",
                                    "class"     => "form-control",
                                    "selected"  => get_asaas_setting("asaas_payment_method"),
                                    "options"   => $payment_methods_options,
                                ));
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label for="asaas_payment_note" class=" col-md-3">Nota: </label>
                            <div class=" col-md-9">
                                <?php
                                echo form_input(array(
                                    "id" => "asaas_payment_note",
                                    "name" => "asaas_payment_note",
                                    "value" => get_asaas_setting("asaas_payment_note"),
                                    "class" => "form-control",
                                    "placeholder" => "Nota do pagamento"
                                ));
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
                </div>

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    "use strict";

    $(document).ready(function () {
        $("#asaas-settings-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 10000});
            }
        });
    });
</script>