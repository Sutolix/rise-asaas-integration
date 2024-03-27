<?php asaas_load_css(array("assets/css/asaas_styles.css")); ?>
<div class="asaas-hello-world p15">
    Value from setting: <?php echo get_asaas_setting("setting_asaas"); ?> <br />
    Language from library: <?php echo app_lang("asaas_hello_world"); ?>
</div>