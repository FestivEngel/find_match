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

$result['status'] = false;
if (isset($_REQUEST['command']) && $_REQUEST['command'] == 'new') {
    if (isset($_REQUEST['token']) && isset($_REQUEST['email']) &&
        isset($_REQUEST['password']) && !PasswordReminder::isExpired($_REQUEST['token'])
    ) {
        $userId1 = PasswordReminder::getUserIdByToken($_REQUEST['token']);
        $userId2 = Member::getIdByEmail($_REQUEST['email']);
        if ((string)$userId1 == (string)$userId2) {
            Member::updatePassword($userId1, $_REQUEST['password']);
            $result['status'] = true;
        }
    }
} else {
    $userId = Member::getIdByEmail($_REQUEST['email2']);
    if ($userId) {
        $passwordReminder = new PasswordReminder();
        $passwordReminder->userId = $userId;
        $passwordReminder->store();
        $mailer = new Mailer();
        $mailer->data['type'] = Mailer::TYPE_REMINDPWD;
        $mailer->data['lang'] = Member::getLangById($userId);
        $mailer->data['email'] = $_REQUEST['email2'];
        $mailer->data['token'] = $passwordReminder->token;
        $mailer->putInQueue();
        $result['status'] = true;
    }
}

echo json_encode($result);