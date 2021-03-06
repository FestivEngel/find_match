<?php
/**
 * Yet Another Dating Site based on socionics
 * PHP, Linux, Apache, Nginx, Twig, MongoDB, Redis, RabbitMQ, AJAX, JQuery, JSON
 *
 * @author Igor Tikhonov <itikhonov83@gmail.com>
 */

abstract class Form {
    /**
     * @return bool
     */
    abstract protected function validate();

    /**
     * @return bool
     */
    abstract protected function process();
}