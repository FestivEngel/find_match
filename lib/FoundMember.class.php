<?php
/**
 * Yet Another Dating Site based on socionics
 * PHP, Linux, Apache, Nginx, Twig, MongoDB, Redis, RabbitMQ, AJAX, JQuery, JSON
 *
 * @author Igor Tikhonov <itikhonov83@gmail.com>
 */

class FoundMember {
    /**
     * @var MongoId|string
     */
    public $id;

    /**
     * @var string
     */
    public $nickname;

    /**
     * @var bool
     */
    public $hasPhoto;

    /**
     * @var string
     */
    public $photoFileName;
}