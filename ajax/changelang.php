<?php
/**
 * Yet Another Dating Site based on socionics
 * PHP, Linux, Apache, Nginx, Twig, MongoDB, Redis, RabbitMQ, AJAX, JQuery, JSON
 *
 * @author Igor Tikhonov <itikhonov83@gmail.com>
 */

header('Content-Type: application/json');

require_once dirname(__FILE__) . '/../lib/autoload.php';

$result['status'] = false;
if (isset($_SESSION['userId'])) {
    $member = new Member();
    $member->id = $_SESSION['userId'];
    $member->lang = $_REQUEST['lang'];
    $member->updateLang();
    $result['status'] = true;
} else {
    $_SESSION['lang'] = $_REQUEST['lang'];
}

echo json_encode($result);