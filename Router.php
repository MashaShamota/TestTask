<?php
require_once 'controllers/EventController.php';

class Router
{
    /**
     * Property: action
     * The action requested
     */
    protected $action = '';

    /**
     * Property: params
     * Request parameters
     */
    protected $params = [];


    /**
     * Router constructor. Check request method and map it to specific action.
     * @throws Exception if method is not supported
     * TODO: Better way to handle and map requests to endpoints
     */
    public function __construct()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'POST':
                //TODO change this to $_POST array, something wrong with my localhost configuration
                $body = file_get_contents("php://input");
                $this->params = json_decode($body, true);
                $this->action = 'updateEventCounter';
                break;
            case 'GET':
                $this->params = $_GET;
                $this->action = 'getStatistics';
                break;
            default:
                throw new Exception('Method ' . $method . ' is not supported.', 1);
                break;
        }
    }

    /**
     * Route mapper to controller action.
     * @throws Exception if action method is not exists.
     */
    public function process()
    {
        $controller = new EventController();
        if (method_exists($controller, $this->action)) {
            return $controller->{$this->action}($this->params);
        } else {
            throw new Exception('Action ' . $this->action . ' is not supported.', 1);
        }
    }

}
