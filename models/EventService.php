<?php
require_once './models/Database.php';
require_once './helpers/SQLQueries.php';

class EventService
{
    /**
     * Property: format
     * GET-response format, defaul jason
     */
    public $format = 'json';

    /**
     * Property: event
     * Type of event
     */
    public $event;

    /**
     * Property: country
     * Country code
     */
    public $country;

    /**
     * Property: topCount
     * Number of top countries for selection
     */
    public $topCount = 5;

    /**
     * Property: allowedFormats
     * Declaration of available formats for GET request results
     */
    protected $allowedFormats = [
        'json',
        'csv'
    ];

    /**
     * Property: eventTypes
     * Declaration of available event types
     */
    protected $eventTypes = [
        'click',
        'view',
        'play'
    ];

    /**
     * Property: dbh
     * Database Handle
     */
    private $dbh;

    /**
     * Get data from database regarding request parameters
     * @param array $format format for response
     * @return array with results or empty set
     */
    public function getEventsStatistics($format)
    {
        if (isset($format['format'])) {
            $this->setParams(['format' => $format['format']]);
        }
        $this->validateFormat();
        $this->connect();
        $query = $this->dbh->prepare(SQLQueries::getSelectTopCountriesQuery($this->topCount));
        $query->execute();
        $countries = $query->fetchAll(PDO::FETCH_COLUMN, 0);

        if (empty($countries)) {
            return [];
        }
        $queryStatistics = $this->dbh->prepare(SQLQueries::getSelectStatistics($countries));
        $queryStatistics->execute();
        $stats = $queryStatistics->fetchAll();
        if (empty($stats)) {
            return [];
        }

        return $stats;
    }

    /**
     * @param $params
     * @return bool status of counter update
     */
    public function incrementEventsCounter($params)
    {
        $this->setParams($params);
        $this->validateRequestParams();
        $this->connect();
        $params = array(':event' => $this->event, ':country' => $this->country, ':date' => date('Y-m-d'));
        $query = $this->dbh->prepare(SQLQueries::getUpdateCounterQuery());
        $query->execute($params);
        $result = $query->rowCount();

        if ($result === 0) {
            $params[':hints'] = 1;
            $query = $this->dbh->prepare(SQLQueries::getInsertEventQuery());
            $query->execute($params);
            $result = $query->rowCount();
        }
        if(!$result) return false;
        return true;
    }

    /**
     * Setter for request parameters
     * @param array $params
     */
    protected function setParams(array $params)
    {
        if (isset($params['format'])) {
            $this->format = $params['format'];
        }
        if (isset($params['event'])) {
            $this->event = $params['event'];
        }
        if (isset($params['country'])) {
            $this->country = $params['country'];
        }
    }

    /**
     * Init DB connection
     */
    private function connect()
    {
        if (!$this->dbh) {
            $db = Database::getInstance();
            $this->dbh = $db->getConnection();
        }
    }

    /**
     * Validation for allowed formats
     * @throws Exception
     */
    private function validateFormat()
    {
        if (!in_array($this->format, $this->allowedFormats)) {
            throw new Exception('Requested format ' . $this->format . ' is not available', 1);
        }
    }

    /**
     * Validation for request parameters
     * @throws Exception
     */
    private function validateRequestParams()
    {
        if (empty($this->event) || empty($this->country)) {
            throw new Exception('Request parameters "country" and "event" cannot be empty.', 1);
        }
        if (!empty($this->event) && !in_array($this->event, $this->eventTypes)) {
            throw new Exception($this->event . ' type is not supported.', 1);
        }

    }
}
