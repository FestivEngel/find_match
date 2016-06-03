<?php
/**
 * Yet Another Dating Site based on socionics
 * PHP, Linux, Apache, Nginx, Twig, MongoDB, Redis, RabbitMQ, AJAX, JQuery, JSON
 *
 * @author Igor Tikhonov <itikhonov83@gmail.com>
 */

require_once dirname(__FILE__) . '/lib/autoload.php';
require_once dirname(__FILE__) . '/vendor/autoload.php';

$member = new Member();
$sendMailForNew = false;
$newUser = false;
$authDone = false;
switch ($_REQUEST['what']) {
    case 'vkauth':
        if (isset($_GET['code'])) {
            $auth = new VKAuth();
            $auth->getUserInfo($_GET['code']);
            $member->authMethod = Auth::METHOD_VK;
            $member->userName = $auth->userName;
            $member->nickname = $member->firstName = $auth->firstName;
            $member->lastName = $auth->lastName;
            $member->gender = $auth->gender;
            if ($auth->hasEmail) {
                $member->email = $auth->email;
                $sendMailForNew = true;
            } else {
                $member->hasEmail = false;
            }

            $authDone = true;
            if (!$member->isInDbVK()) {
                $newUser = true;
            }
        }
        break;

    case 'googleauth':
        if (isset($_GET['code'])) {
            $auth = new GoogleAuth();
            $auth->getUserInfo($_GET['code']);
            $member->authMethod = Auth::METHOD_GOOGLE;
            $member->userName = $auth->userName;
            $member->email = $auth->email;
            $member->nickname = $member->firstName = $auth->firstName;
            $member->lastName = $auth->lastName;
            $sendMailForNew = true;
            $authDone = true;
            if (!$member->isInDb()) {
                $newUser = true;
            }
        }
        break;

    case 'fbauth':
        if (isset($_GET['code'])) {
            $auth = new FBAuth();
            $accessToken = $auth->getAccessToken();
            $auth->getUserInfo($accessToken);
            $member->authMethod = Auth::METHOD_FB;
            $member->userName = $auth->userName;
            $member->email = $auth->email;
            $member->nickname = $member->firstName = $auth->firstName;
            $member->lastName = $auth->lastName;
            $member->gender = $auth->gender;
            $sendMailForNew = true;
            $authDone = true;
            if (!$member->isInDb()) {
                $newUser = true;
            }
        }
        break;

    default;
}

if ($authDone) {
    if ($newUser) {
        $member->bDate = new MongoDate(0);
        $userId = $member->store();
        $savedSearch = new SavedSearch();
        $savedSearch->userId = $userId;
        $savedSearch->createDefault();
        // Send email for new users in case of using Google or FB. Maybe for VK users too
        if ($sendMailForNew) {
            $mailer = new Mailer();
            $mailer->data['type'] = Mailer::TYPE_REGISTER;
            $mailer->data['email'] = $member->email;
            $mailer->data['userName'] = $member->nickname;
            $mailer->data['userId'] = $userId;
            $mailer->putInQueue();
        }
    }

    $_SESSION['userId'] = $member->id;
    Core::redirect2Profile();
} else {
    Core::redirect2Index();
}