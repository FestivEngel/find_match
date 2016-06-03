<?php
/**
 * Yet Another Dating Site based on socionics
 * PHP, Linux, Apache, Nginx, Twig, MongoDB, Redis, RabbitMQ, AJAX, JQuery, JSON
 *
 * @author Igor Tikhonov <itikhonov83@gmail.com>
 */

class SavedSearchesList
{
    public $data = [];

    public function load($userId)
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->searches;
        $cursor = $collection->find(
            array(
                'userId' => $userId instanceof MongoId ? $userId : new MongoId($userId),
                'saved' => true,
            ),
            array(
                '_id' => true,
                'isDefault' => true,
                'name' => true,
            ));
        $cursor->sort(array('ts' => -1));
        foreach ($cursor as $doc) {
            $this->data[] = $doc;
        }
    }
}