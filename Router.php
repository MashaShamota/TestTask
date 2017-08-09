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
     * Constructor: __construct
     */
    public function __construct()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'POST':
                $body = file_get_contents("php://input");
                $this->params = json_decode($body, true);
                $this->action = 'updateEventCounter';
                break;
            case 'GET':
                $this->params = $_GET;
                $this->action = 'getStatisctics';
                break;
            default:
                throw new Exception('Method ' . $method . ' is not supported.', 1);
                break;
        }
    }

    /**
     * Route mapper
     * @return mixed
     * @throws Exception
     */
    public function process()
    {
        $controller = new Controller();
        if (method_exists($controller, $this->action)) {
            return $controller->{$this->action}($this->params);
        } else {
            throw new Exception('Action ' . $this->action . ' is not supported.', 1);
        }
    }

}
