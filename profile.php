<?php
/**
 * Yet Another Dating Site based on socionics
 * PHP, Linux, Apache, Nginx, Twig, MongoDB, Redis, RabbitMQ, AJAX, JQuery, JSON
 *
 * @author Igor Tikhonov <itikhonov83@gmail.com>
 */

require_once dirname(__FILE__) . '/lib/autoload.php';
require_once dirname(__FILE__) . '/vendor/autoload.php';

if (isset($_REQUEST['login'])) {
    $loginForm = new LoginForm();
    if ($loginForm->process()) {
        if (!Core::$landingMode && isset($_SESSION['viewUserId'])) {
            $id = $_SESSION['viewUserId'];
            unset($_SESSION['viewUserId']);
            Core::redirect2View($id);
        } else {
            Core::redirect2Profile();
        }
    }
}

$core = new Core();

$core->redirectWoAuth2Index(array('invite', 'profile', 'view', 'analytics'));
switch ($_REQUEST['what']) {
    case 'login':
        if (isset($_SESSION['userId'])) {
            Core::redirect2Profile();
        }

        $core->args['VKAuthLink'] = VKAuth::getAuthLink();
        $core->args['GoogleAuthLink'] = GoogleAuth::getAuthLink();
        $core->args['FBAuthLink'] = FBAuth::getAuthLink();
        $core->renderProfileLogin();
        break;

    case 'logout':
        unset($_SESSION['userId']);
        Core::redirect2Index();
        break;

    case 'invite':
        if (isset($_SESSION['userId'])) {
            $member = new Member();
            $member->id = $_SESSION['userId'];
            $member->load();
            $core->args['nickname'] = $member->nickname;
            $core->args['sociotype'] = RelationsMatrix::getSocionicsNumCode($member->sociotype);
            $core->args['shareMyType'] = !(Member::getSociotypeById($_SESSION['userId']) == ProfileSubstitutions::ANY);
            $core->renderProfileInvite();
        }
        break;

    case 'profile':
        if (Core::$landingMode) {
            Core::redirect2Thanks();
        }

        if (isset($_SESSION['userId'])) {
            // Member data
            $member = new Member();
            $member->id = $_SESSION['userId'];
            $member->load();
            if (!$member->hasEmail) {
                Core::redirect2Email();
            }

            $core->args['member'] = $member;
            if (!$member->isTestTaken()) {
                $core->args['need2TakeTest'] = true;
            } else {
                $core->args['sociotype'] = $member->sociotype;
            }

            // Photo
            $core->args['hasPhoto'] = $member->hasPhoto;
            $core->args['photoFileName'] = $member->photoFileName;

            // Default search data
            $savedSearch = new SavedSearch();
            $savedSearch->userId = $_SESSION['userId'];
            $savedSearch->loadDefault();
            $core->args['defaultSearch'] = $savedSearch;

            // Codes
            $core->args['socionicsCodes'] = RelationsMatrix::getSocionicsCodes2();

            // Substitutions data
            $profileSubstitutions = new ProfileSubstitutions();
            $core->args['substitutions'] = $profileSubstitutions;

            // Messages data
            $message = new Message();
            $message->receiverId = $_SESSION['userId'];
            $core->args['newMessagesCount'] = $message->getNewCount();

            // Countries
            $core->args['livingCountries'] = Living::$countries;

            // Cities
            $core->args['citiesUnitedStates'] = Living::$citiesUnitedStates;
            $core->args['citiesCanada'] = Living::$citiesCanada;
            $core->args['citiesRussia'] = Living::$citiesRussia;
            $core->args['citiesUkraine'] = Living::$citiesUkraine;
            $core->args['citiesKazakhstan'] = Living::$citiesKazakhstan;
            $core->args['citiesBelarus'] = Living::$citiesBelarus;

            // Slider
            $core->args['relationsType'] = 'Duality';

            // Render
            $core->renderProfileIndex();
        }

        break;

    case 'view':
        if (Core::$landingMode) {
            Core::redirect2Thanks();
        } else {
            if (!isset($_SESSION['userId'])) {
                $_SESSION['viewUserId'] = $_REQUEST['anotherUserId'];
                Core::redirect2Login();
            }
        }

        if (isset($_SESSION['userId'])) {
            $profileView = new ProfileView();
            $profileView->userId = $_SESSION['userId'];
            $profileView->anotherUserId = $_REQUEST['anotherUserId'];
            if (!$profileView->store()) {
                if (Member::need2SendViewAlert($_REQUEST['anotherUserId'])) {
                    $mailer = new Mailer();
                    $mailer->data['type'] = Mailer::TYPE_VIEWS;
                    $mailer->data['lang'] = Member::getLangById($_REQUEST['anotherUserId']);
                    $mailer->data['email'] = Member::getEmailById($_REQUEST['anotherUserId']);
                    $mailer->data['anotherUserName'] = Member::getNicknameById($_SESSION['userId']);
                    $mailer->data['anotherUserId'] = $_SESSION['userId'];
                    $mailer->data['userId'] = $_REQUEST['anotherUserId'];
                    $mailer->putInQueue();
                }
            }

            $core->args['likeDisabled'] = false;
            if (Like::isLiked($_SESSION['userId'], $_REQUEST['anotherUserId']) ||
                (string)$_SESSION['userId'] == (string)$_REQUEST['anotherUserId']
            ) {
                $core->args['likeDisabled'] = true;
            }

            $anotherMember = new Member();
            $anotherMember->id = $_REQUEST['anotherUserId'];
            $anotherMember->load();
            $currentUserSociotype = Member::getSociotypeById($_SESSION['userId']);
            $anotherMember->relations = RelationsMatrix::getIntertypeRelation($currentUserSociotype,
                $anotherMember->sociotype);
            $core->args['anotherMember'] = $anotherMember;
            $profileSubstitutions = new ProfileSubstitutions();
            $core->args['substitutions'] = $profileSubstitutions;
            $core->args['socionicsCodes'] = RelationsMatrix::getSocionicsCodes2();
            $core->args['mutualLike'] = Like::isMutuallyLiked($_SESSION['userId'], $anotherMember->id);
            $core->args['paid'] = Member::getPaidById($_SESSION['userId']);

            // Photo
            $core->args['hasPhoto'] = $anotherMember->hasPhoto;
            $core->args['photoFileName'] = $anotherMember->photoFileName;

            if ((string)$_SESSION['userId'] == (string)$_REQUEST['anotherUserId']) {
                $core->args['myProfile'] = true;
            } else {
                $core->args['myProfile'] = false;
            }

            $core->renderProfileView();
        }
        break;

    case 'analytics':
        if (Core::$landingMode) {
            Core::redirect2Thanks();
        }

        if (isset($_SESSION['userId'])) {
            $analytics = new Analytics();
            $analytics->userId = $_SESSION['userId'];
            $analytics->load();
            $analytics->loadStatistics();
            $core->args['analytics'] = $analytics;
            $member = new Member();
            $member->id = $_SESSION['userId'];
            $member->load();
            $core->args['likesAlerts'] = $member->likesAlerts;
            $core->args['viewsAlerts'] = $member->viewsAlerts;
            $core->args['paid'] = $member->paid;
            $core->renderProfileAnalytics();
        }

        break;

    case 'unsubscribe':
        if (isset($_REQUEST['userId'])) {
            Member::unsubscribe($_REQUEST['userId']);
            $core->renderProfileUnsubscribe();
        }
        break;

    case 'restore':
        if (isset($_REQUEST['token']) && !PasswordReminder::isExpired($_REQUEST['token'])) {
            $core->args['token'] = $_REQUEST['token'];
            $core->renderProfileRestore();
        }
        break;

    case 'email':
        if (Member::getHasEmailById($_SESSION['userId'])) {
            Core::redirect2Profile();
        }

        $core->renderProfileEmail();
        break;

}