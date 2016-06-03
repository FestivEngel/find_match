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
// auth required
$core->redirectWoAuth2Index(array('instructions', 'test', 'results'));

$configuration = Configuration::getInstance();
$core->args['retakeTimeoutSec'] = $configuration->timeForTest;
$core->args['testUnreg'] = false;

switch ($_REQUEST['what']) {
    case 'test':
        if (TestResult::hasResults($_SESSION['userId'])) {
            $core->redirect2TestResults();
        }

        $core->renderTestIndex();
        break;

    case 'results':
        if (isset($_REQUEST['answers'])) {
            $answers = json_decode($_REQUEST['answers']);
            $results = Questionnaire::getResults($answers);
            if (count($results) == 1) {
                $code = $results[0];
                $member = new Member();
                $member->id = $_SESSION['userId'];
                $member->sociotype = RelationsMatrix::getSocionicsCodeNum($code);
                $member->updateSociotype();
                Core::redirect2Thanks();
            } else {
                $testResult = new TestResult();
                $testResult->userId = $_SESSION['userId'];
                $testResult->results = $results;
                $testResult->spentTimeSec = $_REQUEST['spentTimeSec'];
                $testResult->needRetake = $_REQUEST['needRetake'];
                $testResult->store();
            }

            $core->args['resultsCount'] = count($results);
            $core->args['results'] = $results;
            $core->args['resultsJson'] = json_encode($results);
            $core->args['spentTimeSec'] = $_REQUEST['spentTimeSec'];
            $core->args['needRetake'] = $_REQUEST['needRetake'];
            $core->renderTestResults();
        } else if (isset($_REQUEST['itsMe'])) {
            $results = (array)json_decode($_REQUEST['resultsJson']);
            $member = new Member();
            $member->id = $_SESSION['userId'];
            $code = $results[$_REQUEST['currentResult']];
            $member->sociotype = RelationsMatrix::getSocionicsCodeNum($code);
            $member->updateSociotype();
            $testResult = new TestResult();
            $testResult->userId = $_SESSION['userId'];
            $testResult->delete();
            Core::redirect2Thanks();
        } else if (TestResult::hasResults($_SESSION['userId'])) {
            $testResult = new TestResult();
            $testResult->userId = $_SESSION['userId'];
            $testResult->load();
            $core->args['resultsCount'] = count($testResult->results);
            $core->args['results'] = $testResult->results;
            $core->args['resultsJson'] = json_encode($testResult->results);
            $core->args['spentTimeSec'] = $testResult->spentTimeSec;
            $core->args['needRetake'] = $testResult->needRetake;
            $core->renderTestResults();
        } else {
            $core->renderTestInstructions();
        }
        break;

    default:
        if (TestResult::hasResults($_SESSION['userId'])) {
            $core->redirect2TestResults();
        }

        $core->renderTestInstructions();
}