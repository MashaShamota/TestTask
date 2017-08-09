<?php

require_once './models/EventService.php';
require_once './helpers/RequestHelper.php';

class Controller
{

    private $service;

    public function __construct()
    {
        $this->service = new Service();
    }

    /**
     * @param array $params
     * @return array|bool|resource|string
     */
    public function getStatistics($params)
    {
        $params = RequestHelper::cleanInputs($params);
        $data = $this->service->getEventsStatistics($params);
        if ($this->service->format === 'csv') {
            header("Content-type: text/csv");
            header("Content-Disposition: attachment; filename=file.csv");
        } else {
            header('Content-Type: application/json');
        }

        return ResponseHelper::formatGetResponse($data, $this->service->format);
    }

    public function updateEventCounter($params)
    {
        $params = RequestHelper::cleanInputs($params);
        header('Content-Type: application/json');

        return ResponseHelper::formatPostResponse($this->service->incrementEventsCounter($params));
    }

}
