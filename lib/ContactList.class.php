<?php
/**
 * Yet Another Dating Site based on socionics
 * PHP, Linux, Apache, Nginx, Twig, MongoDB, Redis, RabbitMQ, AJAX, JQuery, JSON
 *
 * @author Igor Tikhonov <itikhonov83@gmail.com>
 */

class ContactList
{
    public static function isInList($contact)
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->contacts;
        $contact = $collection->findOne(array(
            'userId' => $contact->userId,
            'anotherUserId' => $contact->anotherUserId
        ));

        return $contact ? true : false;
    }

    /**
     * @param MongoId|string $userId
     */
    public static function deleteContacts($userId) {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->contacts;
        $collection->remove(array('userId' => $userId instanceof MongoId ? $userId : new MongoId($userId)));
    }

    /**
     * @var MongoId|string
     */
    public $userId;

    /**
     * @var Contact
     */
    public $contacts = [];

    public function load()
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->contacts;
        $cursor = $collection->find(array('userId' => $this->userId instanceof MongoId ? $this->userId : new MongoId($this->userId)));
        $cursor->sort(array('lastMessageTs' => -1));
        foreach ($cursor as $document) {
            $contact = new Contact();
            $contact->data = $document;
            $this->contacts[] = $contact;
        }
    }
}