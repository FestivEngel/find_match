<?php
/**
 * Yet Another Dating Site based on socionics
 * PHP, Linux, Apache, Nginx, Twig, MongoDB, Redis, RabbitMQ, AJAX, JQuery, JSON
 *
 * @author Igor Tikhonov <itikhonov83@gmail.com>
 */

require_once dirname(__FILE__) . '/lib/autoload.php';
require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

/**
 * @param string $msg
 */
function sendEmail($msg) {
    $msg = unserialize($msg);
    print_r($msg);
}

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->queue_declare('mails', false, false, false, false);

echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";

$mailer = new Mailer();
$callback = function ($msg) {
    global $mailer;
    echo " [x] Received ", $msg->body, "\n";
    $mailer->serializedMsg = $msg->body;
    $mailer->send();
};

$channel->basic_consume('mails', '', false, true, false, false, $callback);

while (count($channel->callbacks)) {
    $channel->wait();
}