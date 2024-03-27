<?php

namespace Asaas\Config;

use CodeIgniter\Events\Events;

Events::on('pre_system', function () {
    helper("asaas_general");
});