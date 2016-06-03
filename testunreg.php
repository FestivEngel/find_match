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

$configuration = Configuration::getInstance();
$core->args['retakeTimeoutSec'] = $configuration->timeForTest;
$core->args['testUnreg'] = true;

switch ($_REQUEST['what']) {
    case 'test':
        $core->renderTestIndex();
        break;

    case 'results':
        if (isset($_REQUEST['answers'])) {
            $answers = json_decode($_REQUEST['answers']);
            $results = Questionnaire::getResults($answers);
            $core->args['resultsCount'] = count($results);
            $core->args['results'] = $results;
            $core->args['resultsJson'] = json_encode($results);
            $core->args['spentTimeSec'] = $_REQUEST['spentTimeSec'];
            $core->args['needRetake'] = $_REQUEST['needRetake'];
            $core->renderTestResults();
        } else if (isset($_REQUEST['itsMe'])) {
            Core::redirect2Thanks();
        } else {
            $core->renderTestInstructions();
        }
        break;

    default:
        $core->renderTestInstructions();
}