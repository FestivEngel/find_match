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
switch ($_REQUEST['what']) {
    case 'howitworks':
        $core->renderAboutHowItWorks();
        break;

    case 'whyitworks':
        $core->renderAboutWhyItWorks();
        break;

    case 'detailed':
        $core->renderAboutDetailed();
        break;

    case 'options':
        $core->renderAboutOptions();
        break;

    default:
        $core->renderAboutIndex();
}