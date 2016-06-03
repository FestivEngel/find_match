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
    $member = new Member();
    $member->id = $_SESSION['userId'];
    switch ($_REQUEST['command']) {
        case 'activate':
            $member->setActive(true);
            break;

        case 'deactivate':
            $member->setActive(false);
            break;

        case 'delete':
            $member->load();
            $member->delete();
            unset($_SESSION['userId']);
            break;

        case 'matchingOn':
            $member->setMatching(true);
            break;

        case 'matchingOff':
            $member->setMatching(false);
            break;

        case 'update':
            $member->bDay = $_REQUEST['bDate'];
            $member->bMonth = $_REQUEST['bMonth'];
            $member->bYear = $_REQUEST['bYear'];
            $member->updateProfile();
            $savedSearch = new SavedSearch();
            $savedSearch->setAll();
            $savedSearch->pRelationsType = $_REQUEST['pRelationsType'];
            $savedSearch->updateDefault();
            break;

        case 'like':
            $like = new Like();
            $like->userId = $_SESSION['userId'];
            $like->anotherUserId = $_REQUEST['anotherUserId'];
            $like->store();
            if (Member::need2SendLikeAlert($_REQUEST['anotherUserId'])) {
                $mailer = new Mailer();
                $mailer->data['type'] = Mailer::TYPE_LIKES;
                $mailer->data['lang'] = Member::getLangById($_REQUEST['anotherUserId']);
                $mailer->data['email'] = Member::getEmailById($_REQUEST['anotherUserId']);
                $mailer->data['anotherUserName'] = Member::getNicknameById($_SESSION['userId']);
                $mailer->data['anotherUserId'] = $_SESSION['userId'];
                $mailer->data['userId'] = $_REQUEST['anotherUserId'];
                $mailer->putInQueue();
            }
            break;

        case 'likesAlertsOn':
            $member = new Member();
            $member->id = $_SESSION['userId'];
            $member->setLikesAlerts(true);
            break;

        case 'likesAlertsOff':
            $member = new Member();
            $member->id = $_SESSION['userId'];
            $member->setLikesAlerts(false);
            break;

        case 'viewsAlertsOn':
            $member = new Member();
            $member->id = $_SESSION['userId'];
            $member->setViewsAlerts(true);
            break;

        case 'viewsAlertsOff':
            $member = new Member();
            $member->id = $_SESSION['userId'];
            $member->setViewsAlerts(false);
            break;

        case 'invite':
            if (isset($_REQUEST['email']) && isset($_REQUEST['message'])) {
                //echo "Your invitation was sent to " . $_REQUEST['email'];
                $shareMyType = 0;
                if (isset($_REQUEST['shareMyType'])) {
                    $shareMyType = true;
                }

                $inviteKey = new InviteKey();
                $inviteKey->userId = $_SESSION['userId'];
                $inviteKey->email = $_REQUEST['email'];
                $inviteKey->store();

                $mailer = new Mailer();
                $mailer->data['type'] = Mailer::TYPE_INVITE;
                $mailer->data['lang'] = $_SESSION['lang'];
                $mailer->data['email'] = $_REQUEST['email'];
                $mailer->data['message'] = $_REQUEST['message'];
                $mailer->data['shareMyType'] = $shareMyType;
                $mailer->data['userName'] = Member::getNicknameById($_SESSION['userId']);
                if ($shareMyType) {
                    $mailer->data['myType'] = $_REQUEST['myType'];
                }

                $mailer->data['inviteKey'] = $inviteKey->token;
                $mailer->putInQueue();
                $result['email'] = $_REQUEST['email'];
            } else {
                $result['status'] = false;
            }
            break;

        case 'updateEmail':
            if (isset($_SESSION['userId']) && isset($_REQUEST['email'])) {
                Member::updateEmail($_SESSION['userId'], $_REQUEST['email']);
                $mailer = new Mailer();
                $mailer->data['type'] = Mailer::TYPE_REGISTER;
                $mailer->data['lang'] = Member::getLangById($_SESSION['userId']);
                $mailer->data['email'] = $_REQUEST['email'];
                $mailer->data['userName'] = Member::getNicknameById($_SESSION['userId']);
                $mailer->data['userId'] = $_SESSION['userId'];
                $mailer->putInQueue();
            } else {
                $result['status'] = false;
            }
            break;

        default:
            $result['status'] = false;
    }
}

echo json_encode($result);