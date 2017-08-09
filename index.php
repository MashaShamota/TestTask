<?php

require_once 'Router.php';
require_once 'helpers/ResponseHelper.php';

/**
 * Entry point
 * TODO: Exceptions handling should be improved
 */
try {
    $router = new Router();
    print $router->process();
} catch (Exception $e) {
    http_response_code(400);
    header('Content-Type: application/json');
    print ResponseHelper::formatError($e->getMessage());
}
