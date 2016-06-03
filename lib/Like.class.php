<?php
/**
 * Yet Another Dating Site based on socionics
 * PHP, Linux, Apache, Nginx, Twig, MongoDB, Redis, RabbitMQ, AJAX, JQuery, JSON
 *
 * @author Igor Tikhonov <itikhonov83@gmail.com>
 */

class Like
{
    /**
     * @param MongoId|string $userId
     * @param MongoId|string $anotherUserId
     * @return bool
     */
    public static function isLiked($userId, $anotherUserId)
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->likes;
        $count = $collection->count(array(
            'userId' => $userId instanceof MongoId ? $userId : new MongoId($userId),
            'anotherUserId' => $anotherUserId instanceof MongoId ? $anotherUserId : new MongoId($anotherUserId),
        ));

        return $count > 0;
    }

    public static function isMutuallyLiked($userId, $anotherUserId)
    {
        return (Like::isLiked($userId, $anotherUserId) && Like::isLiked($anotherUserId, $userId));
    }

    public static function deleteLikes($userId)
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->likes;
        $collection->remove(array('userId' => $userId instanceof MongoId ? $userId : new MongoId($userId)));
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
        'isNew',
        'ts',
    );

    public function __construct()
    {
        $this->data['ts'] = new MongoDate();
        $this->data['isNew'] = true;
    }

    /**
     * @param string $name
     * @param mixed $value
     */
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

    /**
     * @param $name
     * @return bool|null|string
     */
    public function __get($name)
    {
        if ($name == 'id') {
            return $this->data['_id'];
        }

        if ($name == 'name') {
            return Member::getNicknameById($this->data['anotherUserId']);
        }

        if ($name == 'date') {
            return date('d M Y', $this->data['ts']->sec);
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
        if ($name == 'id' || $name == 'date' || $name == 'name') {
            return true;
        }

        return in_array($name, $this->keys);
    }

    public function store()
    {
        if ((string)$this->data['userId'] == (string)$this->data['anotherUserId']) {
            return;
        }

        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->likes;
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
}