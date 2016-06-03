<?php
/**
 * Yet Another Dating Site based on socionics
 * PHP, Linux, Apache, Nginx, Twig, MongoDB, Redis, RabbitMQ, AJAX, JQuery, JSON
 *
 * @author Igor Tikhonov <itikhonov83@gmail.com>
 */

class Analytics
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * @var array
     */
    private $keys = array(
        '_id',
        'userId',
    );

    /**
     * @var array
     */
    private $recentViews = [];

    /**
     * @var array
     */
    private $recentLikes = [];

    /**
     * @var int
     */
    private $totalUniqueUsers = 0;

    /**
     * @var int
     */
    private $totalUsersReplied = 0;

    /**
     * @var int
     */
    private $totalMessagesSent = 0;

    /**
     * @var int
     */
    private $totalMessagesReceived = 0;

    /**
     * @var int
     */
    private $responseRate = 0;

    public function __construct()
    {
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        if ($name == 'id') {
            $this->data['_id'] = $value instanceof MongoId ? $value : new MongoId($value);
        }

        if ($name == 'userId') {
            $this->data['userId'] = $value instanceof MongoId ? $value : new MongoId($value);

            return;
        }

        if (in_array($name, $this->keys)) {
            $this->data[$name] = $value;
        }
    }

    /**
     * @param $name
     * @return array|null|string
     */
    public function __get($name)
    {
        if ($name == 'id') {
            return $this->data['_id'];
        }

        if ($name == 'recentViews') {
            return $this->recentViews;
        }

        if ($name == 'recentLikes') {
            return $this->recentLikes;
        }

        if ($name == 'totalUniqueUsers') {
            return $this->totalUniqueUsers;
        }

        if ($name == 'totalUsersReplied') {
            return $this->totalUsersReplied;
        }

        if ($name == 'totalMessagesSent') {
            return $this->totalMessagesSent;
        }

        if ($name == 'totalMessagesReceived') {
            return $this->totalMessagesReceived;
        }

        if ($name == 'responseRate') {
            return $this->responseRate;
        }

        if (in_array($name, $this->keys)) {
            return $this->data[$name];
        }

        return null;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function __isset($name)
    {
        if (in_array($name, $this->keys)) {
            return true;
        }

        if ($name == 'recentViews') {
            return true;
        }

        if ($name == 'recentLikes') {
            return true;
        }

        if ($name == 'totalUniqueUsers') {
            return true;
        }

        if ($name == 'totalUsersReplied') {
            return true;
        }

        if ($name == 'totalMessagesSent') {
            return true;
        }

        if ($name == 'totalMessagesReceived') {
            return true;
        }

        if ($name == 'responseRate') {
            return true;
        }

        return false;
    }

    public function load()
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->views;
        $cursor = $collection->find(
            array(
                'anotherUserId' => $this->data['userId'],
            ),
            array(
                'ts' => true,
                'userId' => true,
            )
        );
        $cursor->sort(array('ts' => -1));
        foreach ($cursor as $doc) {
            $profileView = new ProfileView();
            $profileView->ts = $doc['ts'];
            $profileView->anotherUserId = $doc['userId'];
            $this->recentViews[] = $profileView;
        }

        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->likes;
        $cursor = $collection->find(
            array(
                'anotherUserId' => $this->data['userId'],
            ),
            array(
                'ts' => true,
                'userId' => true,
            )
        );
        $cursor->sort(array('ts' => -1));
        foreach ($cursor as $doc) {
            //var_dump($doc);
            $like = new Like();
            $like->ts = $doc['ts'];
            $like->anotherUserId = $doc['userId'];
            $this->recentLikes[] = $like;
        }
    }

    public function loadStatistics()
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->contacts;
        $cursor = $collection->find(
            array(
                'userId' => $this->data['userId'],
            ),
            array(
                'totalMessagesSent' => true,
                'totalMessagesReceived' => true,
            )
        );
        foreach ($cursor as $doc) {
            $this->totalUniqueUsers++;
            $this->totalMessagesSent += $doc['totalMessagesSent'];
            $this->totalMessagesReceived += $doc['totalMessagesReceived'];
            if ($doc['totalMessagesReceived'] != 0) {
                $this->totalUsersReplied++;
            }

            if ($this->totalUniqueUsers != 0) {
                $this->responseRate = (int)($this->totalUsersReplied / $this->totalUniqueUsers * 100);
            }
        }
    }
}