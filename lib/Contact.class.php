<?php
/**
 * Yet Another Dating Site based on socionics
 * PHP, Linux, Apache, Nginx, Twig, MongoDB, Redis, RabbitMQ, AJAX, JQuery, JSON
 *
 * @author Igor Tikhonov <itikhonov83@gmail.com>
 */

class Contact
{
    /**
     * @var array
     */
    private $keys = array(
        '_id',
        'userId',
        'anotherUserId',
        'lastMessageTs',
        'totalMessagesReceived',
        'totalMessagesSent',
    );

    /**
     * @var array
     */
    private $data = [];

    public function __construct()
    {
        $this->data['lastMessageTs'] = new MongoDate();
        $this->data['totalMessagesReceived'] = 0;
        $this->data['totalMessagesSent'] = 0;
    }

    /**
     * @param string $name
     * @param string $value
     */
    public function __set($name, $value)
    {
        if ($name == 'data') {
            $this->data = $value;
        }

        if ($name == 'id') {
            $this->data['_id'] = $value instanceof MongoId ? $value : new MongoId($value);

            return;
        }

        if ($name == 'userId') {
            $this->data['userId'] = $value instanceof MongoId ? $value : new MongoId($value);

            return;
        }

        if ($name == 'anotherUserId') {
            $this->data['anotherUserId'] = $value instanceof MongoId ? $value : new MongoId($value);

            return;
        }

        if (in_array($name, $this->keys)) {
            $this->data[$name] = $value;
        }
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        if ($name == 'data') {
            return $this->data;
        }

        if ($name == 'id') {
            return $this->data['_id'];
        }

        if ($name == 'lastMessageTs') {
            return date('d M Y', $this->data['lastMessageTs']->sec);
        }

        if ($name == 'anotherUserName') {
            return Member::getNicknameById($this->data['anotherUserId']);
        }

        if (in_array($name, $this->keys)) {
            return $this->data[$name];
        }
    }

    public function __isset($name)
    {
        if ($name == 'id') {
            return true;
        }

        if ($name == 'anotherUserName') {
            return true;
        }

        if (in_array($name, $this->keys)) {
            return true;
        }

        return false;
    }

    public function load()
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->contacts;
        $this->data = $collection->findOne(
            array(
                'userId' => $this->data['userId'],
            ),
            $this->keys
        );
    }

    public function store()
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->contacts;
        $collection->insert($this->data);
    }

    public function updateLastMessageDate()
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->contacts;
        $newdata = array(
            '$set' => array(
                'lastMessageTs' => new MongoDate(),
            ),
        );
        $collection->update(
            array('_id' => $this->data['_id']),
            $newdata);
    }

    /**
     * @param int $num
     */
    public function incrementTotalMessagesReceived($num)
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->contacts;
        $newdata = array(
            '$inc' => array(
                'totalMessagesReceived' => $num,
            ),
            '$set' => array(
                'lastMessageTs' => new MongoDate(),
            ),
        );
        $collection->update(
            array(
                'userId' => $this->data['userId'],
                'anotherUserId' => $this->data['anotherUserId'],
            ),
            $newdata);
    }

    /**
     * @param int $num
     */
    public function incrementTotalMessagesSent($num)
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->contacts;
        $newdata = array(
            '$inc' => array(
                'totalMessagesSent' => $num,
            ),
            '$set' => array(
                'lastMessageTs' => new MongoDate(),
            ),
        );
        $collection->update(
            array(
                'userId' => $this->data['userId'],
                'anotherUserId' => $this->data['anotherUserId'],
            ),
            $newdata);
    }
}