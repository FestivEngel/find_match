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
if (isset($_SESSION['userId']) && isset($_REQUEST['command'])) {
    switch ($_REQUEST['command']) {
        case 'getNewCount':
            $message = new Message();
            $message->receiverId = $_SESSION['userId'];
            $result['newCount'] = $message->getNewCount();
            $result['status'] = true;
            break;

        case 'newMsgsAlertsOn':
            $member = new Member();
            $member->id = $_SESSION['userId'];
            $member->setNewMsgsAlerts(true);
            $result['status'] = true;
            break;

        case 'newMsgsAlertsOff':
            $member = new Member();
            $member->id = $_SESSION['userId'];
            $member->setNewMsgsAlerts(false);
            $result['status'] = true;
            break;

        // Contact list
        case 'getContactsList':
            break;

        // Messages
        case 'loadAllMessages':
            break;

        case 'loadNewMessages':
            $conversation = new Conversation();
            $conversation->userId = $_SESSION['userId'];
            $conversation->anotherUserId = $_REQUEST['anotherUserId'];
            $conversation->loadNew();
            $newCount = $conversation->getMessagesCount();
            $result['newCount'] = $newCount;
            if ($newCount != 0) {
                $result['messages'] = [];
            }

            for ($i = 0; $i < $newCount; $i++) {
                $message = array(
                    'senderName' => $conversation->messages[$i]->senderName,
                    'receiverName' => $conversation->messages[$i]->receiverName,
                    'subject' => $conversation->messages[$i]->subject,
                    'body' => $conversation->messages[$i]->body,
                );
                $result['messages'][] = $message;
            }

            $result['status'] = true;
            break;

        case 'sendMessage':
            // Contact
            $contact1 = new Contact();
            $contact1->userId = $_SESSION['userId'];
            $contact1->anotherUserId = $_REQUEST['anotherUserId'];
            if (!ContactList::isInList($contact1)) {
                $contact1->totalMessagesSent = 1;
                $contact1->store();
            } else {
                $contact1->incrementTotalMessagesSent(1);
            }

            $contact2 = new Contact();
            $contact2->userId = $_REQUEST['anotherUserId'];
            $contact2->anotherUserId = $_SESSION['userId'];
            if (!ContactList::isInList($contact2)) {
                $contact2->totalMessagesReceived = 1;
                $contact2->store();
            } else {
                $contact2->incrementTotalMessagesReceived(1);
            }

            // Message
            $message = new Message();
            $message->senderId = $_SESSION['userId'];
            $message->receiverId = $_REQUEST['anotherUserId'];
            $message->subject = $_REQUEST['subject'];
            $message->body = $_REQUEST['body'];
            $message->send();
            // Mark as viewed
            $profileView = new ProfileView();
            $profileView->userId = $_SESSION['userId'];
            $profileView->anotherUserId = $_REQUEST['anotherUserId'];
            $profileView->store();

            $result['status'] = true;
            break;
    }
}

echo json_encode($result);