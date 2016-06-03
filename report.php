<?php
/**
 * Yet Another Dating Site based on socionics
 * PHP, Linux, Apache, Nginx, Twig, MongoDB, Redis, RabbitMQ, AJAX, JQuery, JSON
 *
 * @author Igor Tikhonov <itikhonov83@gmail.com>
 */

require_once dirname(__FILE__) . '/lib/autoload.php';
require_once dirname(__FILE__) . '/vendor/autoload.php';

if (isset($_SESSION['userId']) && isset($_REQUEST['anotherUserId'])) {
    $core = new Core();
    $core->args['anotherUserId'] = $_REQUEST['anotherUserId'];
    $core->renderReportIndex();
}
