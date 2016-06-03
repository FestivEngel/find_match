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
$member->authMethod = Auth::METHOD_REGFORM;
$member->email = $_REQUEST['email'];
$result['status'] = false;
if (Auth::checkEmail($member->email)) {
    $result['status'] = $member->isInDb();
}

echo json_encode($result);