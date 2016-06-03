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
$core->redirectWoAuth2Index(array('purchase'));

$mailer = new Mailer();
$configuration = Configuration::getInstance();
$mailer->data['type'] = Mailer::TYPE_PURCHASE;
$mailer->data['lang'] = Member::getLangById($_SESSION['userId']);
$mailer->data['email'] = Member::getEmailById($_SESSION['userId']);
$mailer->data['userId'] = $_SESSION['userId'];
$mailer->data['userName'] = Member::getNicknameById($_SESSION['userId']);
$mailer->data['membership'] = Membership::LIFETIME;
$mailer->putInQueue();

// TODO: for testing purposes
Member::setPaid($_SESSION['userId']);

$core->renderPurchaseIndex();