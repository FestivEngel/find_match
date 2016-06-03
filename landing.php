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
if (isset($_REQUEST['register'])) {
    $regForm = new RegForm();
    if ($regForm->process()) {
        $mailer = new Mailer();
        $mailer->data['type'] = Mailer::TYPE_REGISTER;
        $mailer->data['lang'] = $_SESSION['lang'];
        $mailer->data['email'] = $_REQUEST['email'];
        $mailer->data['userName'] = Member::getNicknameById($_SESSION['userId']);
        $mailer->data['userId'] = $_SESSION['userId'];
        $mailer->putInQueue();
        unset($_SESSION['formToken']);
        Core::redirect2Thanks();
    }
}

$core->redirectWoAuth2Index(array('thanks'));

if (!isset($_REQUEST['what'])) {
    $what = "";
} else {
    $what = $_REQUEST['what'];
}

switch ($what) {
    case 'thanks':
        if (isset($_SESSION['userId'])) {
            $member = new Member();
            $member->id = $_SESSION['userId'];
            $member->load();
            $core->args['nickname'] = $member->nickname;
            if (!$member->isTestTaken()) {
                $core->args['need2TakeTest'] = true;
            } else {
                $core->args['sociotype'] = $member->code;
            }

            $core->renderLandingThanks();
        } else {
            header('Location: ' . $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/');
        }
        break;

    case 'relations':
        $core->renderLandingRelations();
        break;

    default:
        if (isset($_SESSION['userId'])) {
            Core::redirect2Profile();
        } else {
            $formToken = md5(uniqid('', true));
            $_SESSION['formToken'] = $formToken;
            $core->args['formToken'] = $formToken;
            $core->args['VKAuthLink'] = VKAuth::getAuthLink();
            $core->args['GoogleAuthLink'] = GoogleAuth::getAuthLink();
            $core->args['FBAuthLink'] = FBAuth::getAuthLink();
            if (isset($_REQUEST['inviteToken'])) {
                $core->args['haveInviteToken'] = true;
                $core->args['inviteToken'] = $_REQUEST['inviteToken'];
            } else {
                $core->args['haveInviteToken'] = false;
            }

            $core->renderLandingIndex();
        }

}