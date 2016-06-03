<?php
/**
 * Yet Another Dating Site based on socionics
 * PHP, Linux, Apache, Nginx, Twig, MongoDB, Redis, RabbitMQ, AJAX, JQuery, JSON
 *
 * @author Igor Tikhonov <itikhonov83@gmail.com>
 */

class TestResult
{
    /**
     * @param MongoId|string $userId
     * @return bool
     */
    public static function hasResults($userId)
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->testresults;
        $count = $collection->count(array(
            'userId' => $userId instanceof MongoId ? $userId : new MongoId($userId),
        ));

        return $count > 0;
    }

    /**
     * @var MongoId|string
     */
    public $userId;

    /**
     * @var array
     */
    public $results = [];

    /**
     * @var int
     */
    public $spentTimeSec;

    public $needRetake;

    public function store()
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->testresults;
        $newdata = array(
            'userId' => $this->userId instanceof MongoId ? $this->userId : new MongoId($this->userId),
            'results' => $this->results,
            'spentTimeSec' => $this->spentTimeSec,
            'needRetake' => $this->needRetake,
        );
        $collection->insert($newdata);
    }

    public function load()
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->testresults;
        $data = $collection->findOne(array(
            'userId' => $this->userId instanceof MongoId ? $this->userId : new MongoId($this->userId),
        ));
        $this->results = $data['results'];
        $this->spentTimeSec = $data['spentTimeSec'];
        $this->needRetake = $data['needRetake'];
    }

    public function delete()
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->testresults;
        $collection->remove(array('userId' => $this->userId instanceof MongoId ? $this->userId : new MongoId($this->userId)));
    }
}