<?php
/**
 * Yet Another Dating Site based on socionics
 * PHP, Linux, Apache, Nginx, Twig, MongoDB, Redis, RabbitMQ, AJAX, JQuery, JSON
 *
 * @author Igor Tikhonov <itikhonov83@gmail.com>
 */

header('Content-Type: application/json');

require_once dirname(__FILE__) . '/../lib/autoload.php';

$member = new Member();
$member->email = $_REQUEST['email'];
$member->load();
$result['status'] = false;
if (Auth::comparePasswords($_REQUEST['password'], $member->password)) {
    $_SESSION['userId'] = $member->id;
    $result['status'] = true;
}

echo json_encode($result);