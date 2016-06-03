<?php
/**
 * Yet Another Dating Site based on socionics
 * PHP, Linux, Apache, Nginx, Twig, MongoDB, Redis, RabbitMQ, AJAX, JQuery, JSON
 *
 * @author Igor Tikhonov <itikhonov83@gmail.com>
 */

class PasswordReminder
{
    /**
     * @var MangoId|string
     */
    public $userId;

    /**
     * @var string
     */
    public $token;

    public function store()
    {
        $this->token = Crypto::getToken(10);
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->restorepwd;
        $newdata = array(
            'userId' => $this->userId,
            'ts' => new MongoDate(),
            'token' => $this->token,
        );
        $collection->update(
            array('userId' => $this->userId),
            $newdata,
            array('upsert' => true));
    }

    /**
     * @param string $token
     * @return bool
     */
    public static function isExpired($token)
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->restorepwd;
        $cursor = $collection->find(array(
            'token' => $token,
        ));
        $cursor->sort(array('ts' => -1));
        foreach ($cursor as $doc) {
            $ts1 = $doc['ts'];
            $ts1 = $ts1->sec;
            $ts2 = new DateTime();
            $ts2 = $ts2->getTimestamp();
            $configuration = Configuration::getInstance();
            $restorePasswordTokenTTL = $configuration->restorePasswordTokenTTL;
            $diff = $ts2 - $ts1;
            if ($diff > $restorePasswordTokenTTL) {
                return true;
            } else {
                return false;
            }
        }

        return false;
    }

    /**
     * @param string $token
     * @return null
     */
    public static function getUserIdByToken($token)
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->restorepwd;
        $cursor = $collection->find(array(
            'token' => $token,
        ));
        $cursor->sort(array('ts' => -1));
        foreach ($cursor as $doc) {
            return $doc['userId'];
        }

        return null;
    }
}