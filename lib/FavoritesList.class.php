<?php
/**
 * Yet Another Dating Site based on socionics
 * PHP, Linux, Apache, Nginx, Twig, MongoDB, Redis, RabbitMQ, AJAX, JQuery, JSON
 *
 * @author Igor Tikhonov <itikhonov83@gmail.com>
 */

class FavoritesList
{
    /**
     * @var MongoId|string
     */
    public $userId;

    /**
     * @var array
     */
    public $favorites = [];

    /**
     * @var array
     */
    public $favoritesAnotherUsersIds = [];

    public function load()
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->favorites;
        $cursor = $collection->find(array(
            'userId' => $this->userId,
        ));
        foreach ($cursor as $doc) {
            $member = new Member();
            $member->id = $doc['anotherUserId'];
            if ($member->load() && $member->active) {
                $this->favorites[] = $member;
            }
        }
    }

    public function loadAnotherUsersIds()
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->favorites;
        $cursor = $collection->find(array(
            'userId' => $this->userId,
        ), array(
            'anotherUserId' => true,
        ));
        foreach ($cursor as $doc) {
            $this->favoritesAnotherUsersIds[] = $doc['anotherUserId'];
        }
    }

    /**
     * @param $id
     * @return bool
     */
    public function isInFavorites($id)
    {
        return in_array($id, $this->favoritesAnotherUsersIds);
    }
}