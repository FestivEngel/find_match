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

$core->args['VKAuthLink'] = VKAuth::getAuthLink();
$core->args['GoogleAuthLink'] = GoogleAuth::getAuthLink();
$core->args['FBAuthLink'] = FBAuth::getAuthLink();
$core->renderRegisterIndex();