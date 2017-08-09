<?php

require_once 'Router.php';
require_once 'helpers/ResponseHelper.php';

try {
    $router = new Router();
    $router->process();
} catch (Exception $e) {
    http_response_code(400);
    return ResponseHelper::formatError($e->getMessage());
}
