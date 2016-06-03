<?php
/**
 * Yet Another Dating Site based on socionics
 * PHP, Linux, Apache, Nginx, Twig, MongoDB, Redis, RabbitMQ, AJAX, JQuery, JSON
 *
 * @author Igor Tikhonov <itikhonov83@gmail.com>
 */

class ProfileView
{
    /**
     * @param MongoId|string $userId
     * @param MongoId|string $anotherUserId
     * @return bool
     */
    public static function isViewed($userId, $anotherUserId)
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->views;
        $count = $collection->count(array(
            'userId' => $userId instanceof MongoId ? $userId : new MongoId($userId),
            'anotherUserId' => $anotherUserId instanceof MongoId ? $anotherUserId : new MongoId($anotherUserId),
        ));

        return $count > 0;
    }

    public static function deleteViews($userId)
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->views;
        $collection->remove(array('userId' => $userId instanceof MongoId ? $userId : new MongoId($userId)));
    }

    /**
     * @var array
     */
    private $keys = array(
        '_id',
        'userId', // Viewer profile id
        'anotherUserId', // Viewed profile id
        'isNew',
        'ts',
    );

    /**
     * @var array
     */
    private $data = [];

    public function __construct()
    {
        $this->data['isNew'] = false;
        $this->data['ts'] = new MongoDate();
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
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

    /**
     * @return bool
     */
    public function store()
    {
        if ((string)$this->data['userId'] == (string)$this->data['anotherUserId']) {
            return;
        }

        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->views;
        unset($this->data['_id']);
        $result = $collection->update(
            array(
                'userId' => $this->data['userId'],
                'anotherUserId' => $this->data['anotherUserId'],
            ),
            $this->data,
            array('upsert' => true)
        );

        return $result['updatedExisting'];
    }

    public function delete()
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->views;
        $collection->remove(array(
            'userId' => $this->data['userId'],
            'anotherUserId' => $this->data['anotherUserId'],
        ));
    }
}