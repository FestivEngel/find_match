<?php
/**
 * Yet Another Dating Site based on socionics
 * PHP, Linux, Apache, Nginx, Twig, MongoDB, Redis, RabbitMQ, AJAX, JQuery, JSON
 *
 * @author Igor Tikhonov <itikhonov83@gmail.com>
 */

require_once dirname(__FILE__) . '/lib/autoload.php';
require_once dirname(__FILE__) . '/vendor/autoload.php';

$core = new Core();
if (!isset($_SESSION['userId'])) {
    $core->redirect2Index();
}

$member = new Member();
$member->id = $_SESSION['userId'];
$member->load();

if (isset($_REQUEST['what']) &&
    $_REQUEST['what'] == 'send' &&
    isset($_REQUEST['anotherUserId'])
) {
    $contact = new Contact();
    $contact->userId = $_SESSION['userId'];
    $contact->anotherUserId = $_REQUEST['anotherUserId'];
    if (!ContactList::isInList($contact)) {
        $contact->store();
    }

    $core->args['anotherUserId'] = $_REQUEST['anotherUserId'];
    $core->args['anotherUserName'] = Member::getNicknameById($_REQUEST['anotherUserId']);
    $conversation = new Conversation();
    $conversation->userId = $_SESSION['userId'];
    $conversation->anotherUserId = $_REQUEST['anotherUserId'];
    $conversation->load();
    $core->args['messages'] = $conversation->messages;
    $core->args['senderName'] = Member::getNicknameById($_SESSION['userId']);
    $core->args['receiverName'] = Member::getNicknameById($_REQUEST['anotherUserId']);
    $core->renderMessagesSend();
} else {
    $contactList = new ContactList();
    $contactList->userId = $_SESSION['userId'];
    $contactList->load();
    $core->args['newMsgsAlerts'] = $member->newMsgsAlerts;
    $core->args['contacts'] = $contactList->contacts;
    // Messages data
    $message = new Message();
    $message->receiverId = $_SESSION['userId'];
    $core->args['newMessagesCount'] = $message->getNewCount();
    $core->renderMessagesIndex();
}