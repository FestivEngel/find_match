<?php
/**
 * Yet Another Dating Site based on socionics
 * PHP, Linux, Apache, Nginx, Twig, MongoDB, Redis, RabbitMQ, AJAX, JQuery, JSON
 *
 * @author Igor Tikhonov <itikhonov83@gmail.com>
 */

class Cache
{
    private static $instance = null;

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new Cache();
        }

        return self::$instance;
    }

    private function __construct()
    {
    }

    /**
     * @param Member $member
     */
    public function storeMemberData($member)
    {
        $cacheClient = new Predis\Client();
        $cacheClient->set($member->id, $member->data);
    }

    /**
     * @param Member $member
     * @return bool
     */
    public function getMemberData($member)
    {
        $cacheClient = new Predis\Client();
        $data = $cacheClient->get($member->id);
        if ($data) {
            $member->data = json_decode($data);

            return true;
        }

        return false;
    }
}