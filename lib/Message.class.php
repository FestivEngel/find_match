<?php
/**
 * Yet Another Dating Site based on socionics
 * PHP, Linux, Apache, Nginx, Twig, MongoDB, Redis, RabbitMQ, AJAX, JQuery, JSON
 *
 * @author Igor Tikhonov <itikhonov83@gmail.com>
 */

class Message
{
    /**
     * @var array
     */
    private $keys = array(
        '_id',
        'senderId',
        'receiverId',
        'isViewed',
        'ts',
        'subject',
        'body',
    );

    /**
     * @var array
     */
    private $data = [];

    public function __construct()
    {
        $this->data['ts'] = new MongoDate();
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        if ($name == 'data') {
            $this->data = $value;

            return;
        }

        if ($name == 'id') {
            $this->data['_id'] = $value instanceof MongoId ? $value : new MongoId($value);

            return;
        }

        if ($name == 'senderId') {
            $this->data['senderId'] = $value instanceof MongoId ? $value : new MongoId($value);

            return;
        }

        if ($name == 'receiverId') {
            $this->data['receiverId'] = $value instanceof MongoId ? $value : new MongoId($value);

            return;
        }

        if (in_array($name, $this->keys)) {
            $this->data[$name] = $value;
        }
    }

    /**
     * @param string $name
     * @return null|string
     */
    public function __get($name)
    {
        if ($name == 'id') {
            return $this->data['_id'];
        }

        if ($name == 'senderName') {
            return Member::getNicknameById($this->data['senderId']);
        }

        if ($name == 'receiverName') {
            return Member::getNicknameById($this->data['receiverId']);
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

        if ($name == 'senderName' || $name == 'receiverName') {
            return true;
        }
    }

    public function send()
    {
        $data = array(
            'senderId' => $this->data['senderId'] instanceof MongoId ? $this->data['senderId'] : new MongoId($this->data['senderId']),
            'receiverId' => $this->data['receiverId'] instanceof MongoId ? $this->data['receiverId'] : new MongoId($this->data['receiverId']),
            'isViewed' => false,
            'ts' => $this->data['ts'],
            'subject' => $this->data['subject'],
            'body' => $this->data['body'],
        );
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->messages;
        $collection->insert($data);
    }

    /**
     * @return int
     */
    public function getNewCount()
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->messages;
        $count = $collection->count(array('$and' =>
            array(
                array('receiverId' => $this->data['receiverId'] instanceof MongoId ? $this->data['receiverId'] : new MongoId($this->data['receiverId'])),
                array('isViewed' => false)
            )));

        return $count;
    }

    public function setViewed()
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->messages;
        $newdata = array(
            '$set' => array(
                'isViewed' => true,
            ),
        );
        $collection->update(
            array('_id' => $this->data['_id']),
            $newdata);
    }
}