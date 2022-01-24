<?php

use Kishlin\Apps\RPGIdleGame\Backend\RPGIdleGameKernel;

require_once dirname(__DIR__).'/../../../vendor/autoload_runtime.php';

return function (array $context) {
    return new RPGIdleGameKernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
