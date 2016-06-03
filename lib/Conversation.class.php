<?php
/**
 * Yet Another Dating Site based on socionics
 * PHP, Linux, Apache, Nginx, Twig, MongoDB, Redis, RabbitMQ, AJAX, JQuery, JSON
 *
 * @author Igor Tikhonov <itikhonov83@gmail.com>
 */

class Conversation
{
    /**
     * @var array
     */
    public $messages = [];

    /**
     * @var array
     */
    private $data = [];

    /**
     * @return int
     */
    public function getMessagesCount()
    {
        return count($this->messages);
    }

    public function load()
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->messages;
        $cursor = $collection->find(array('$or' => array(
                array('$and' => array(
                    array('senderId' => $this->data['userId']),
                    array('receiverId' => $this->data['anotherUserId'])
                )),
                array('$and' => array(
                    array('senderId' => $this->data['anotherUserId']),
                    array('receiverId' => $this->data['userId'])
                ))
            ))
        );
        $cursor->sort(array('ts' => 1));
        foreach ($cursor as $doc) {
            $message = new Message();
            $message->data = $doc;
            $message->setViewed();
            $this->messages[] = $message;
        }
    }

    public function loadNew()
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->messages;
        $cursor = $collection->find(array('$and' => array(
            array('senderId' => $this->data['anotherUserId']),
            array('receiverId' => $this->data['userId']),
            array('isViewed' => false)
        )));
        foreach ($cursor as $doc) {
            $message = new Message();
            $message->data = $doc;
            $message->setViewed();
            $this->messages[] = $message;
        }
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        if ($name == 'userId') {
            $this->data['userId'] = $value instanceof MongoId ? $value : new MongoId($value);

            return;
        }

        if ($name == 'anotherUserId') {
            $this->data['anotherUserId'] = $value instanceof MongoId ? $value : new MongoId($value);

            return;
        }
    }

    /**
     * @param string $name
     * @return array|null
     */
    public function __get($name)
    {
        if ($name == 'data') {
            return $this->data;
        }

        if ($name == 'userId' || $name == 'anotherUserId') {
            return $this->data[$name];
        }

        return null;
    }
}