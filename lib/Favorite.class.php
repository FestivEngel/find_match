<?php
/**
 * Yet Another Dating Site based on socionics
 * PHP, Linux, Apache, Nginx, Twig, MongoDB, Redis, RabbitMQ, AJAX, JQuery, JSON
 *
 * @author Igor Tikhonov <itikhonov83@gmail.com>
 */

class Favorite
{
    /**
     * @param MongoId|string $userId
     * @param MongoId|string $anotherUserId
     * @return bool
     */
    public static function isFavorite($userId, $anotherUserId)
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->favorites;
        $count = $collection->count(array(
            'userId' => $userId instanceof MongoId ? $userId : new MongoId($userId),
            'anotherUserId' => $anotherUserId instanceof MongoId ? $anotherUserId : new MongoId($anotherUserId),
        ));

        return $count > 0;
    }

    /**
     * @var array
     */
    private $data = [];

    /**
     * @var array
     */
    private $keys = array(
        '_id',
        'userId', // Liked by id
        'anotherUserId', // Liked profile id
        'ts',
    );

    public function __construct()
    {
        $this->data['ts'] = new MongoDate();
    }

    public function __set($name, $value)
    {
        if ($name == 'id') {
            $this->data['_id'] = $value;

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

    public function __get($name)
    {
        if ($name == 'id') {
            return $this->data['_id'];
        }

        if (in_array($name, $this->keys)) {
            return $this->data[$name];
        }

        return null;
    }

    public function store()
    {
        if ((string)$this->data['userId'] == (string)$this->data['anotherUserId']) {
            return;
        }

        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->favorites;
        unset($this->data['_id']);
        $collection->update(
            array(
                'userId' => $this->data['userId'],
                'anotherUserId' => $this->data['anotherUserId'],
            ),
            $this->data,
            array('upsert' => true)
        );
    }

    public function delete()
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->favorites;
        $collection->remove(array(
            'userId' => $this->data['userId'],
            'anotherUserId' => $this->data['anotherUserId'],
        ));
    }
}