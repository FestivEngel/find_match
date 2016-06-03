<?php
/**
 * Yet Another Dating Site based on socionics
 * PHP, Linux, Apache, Nginx, Twig, MongoDB, Redis, RabbitMQ, AJAX, JQuery, JSON
 *
 * @author Igor Tikhonov <itikhonov83@gmail.com>
 */

header('Content-Type: application/json');

require_once dirname(__FILE__) . '/../lib/autoload.php';
require_once dirname(__FILE__) . '/../vendor/autoload.php';

$result['status'] = true;
if (isset($_SESSION['userId']) && isset($_REQUEST['command'])) {
    switch ($_REQUEST['command']) {
        case 'send':
            $mailer = new Mailer();
            $configuration = Configuration::getInstance();
            $mailer->data['type'] = Mailer::TYPE_COMPLAINT;
            $mailer->data['lang'] = 'en';
            $mailer->data['email'] = $configuration->emailForComplaints;
            $mailer->data['user1Id'] = $_SESSION['userId'];
            $mailer->data['user1Nickname'] = Member::getNicknameById($_SESSION['userId']);
            $mailer->data['user1Email'] = Member::getEmailById($_SESSION['userId']);
            $mailer->data['user2Id'] = $_REQUEST['anotherUserId'];
            $mailer->data['user2Email'] = Member::getEmailById($_REQUEST['anotherUserId']);
            $mailer->data['user2Nickname'] = Member::getNicknameById($_REQUEST['anotherUserId']);
            $mailer->data['message'] = $_REQUEST['message'];
            $mailer->putInQueue();
            break;

        default:
            $result['status'] = false;
    }
}

echo json_encode($result);