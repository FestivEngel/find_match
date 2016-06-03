<?php
/**
 * Yet Another Dating Site based on socionics
 * PHP, Linux, Apache, Nginx, Twig, MongoDB, Redis, RabbitMQ, AJAX, JQuery, JSON
 *
 * @author Igor Tikhonov <itikhonov83@gmail.com>
 */

class ProfileViewsList {
    /**
     * @var MongoId|string
     */
    public $userId;

    /**
     * @var array
     */
    public $profileViewsAnotherUsersIds = [];

    public function loadAnotherUsersIds()
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->views;
        $cursor = $collection->find(array(
            'userId' => $this->userId,
        ), array(
            'anotherUserId' => true,
        ));
        foreach ($cursor as $doc) {
            $this->profileViewsAnotherUsersIds[] = $doc['anotherUserId'];
        }
    }

    /**
     * @param $id
     * @return bool
     */
    public function isProfileViewed($id)
    {
         return in_array($id instanceof MongoId ? $id : new MongoId($id), $this->profileViewsAnotherUsersIds);
    }
}