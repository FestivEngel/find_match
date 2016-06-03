<?php
/**
 * Yet Another Dating Site based on socionics
 * PHP, Linux, Apache, Nginx, Twig, MongoDB, Redis, RabbitMQ, AJAX, JQuery, JSON
 *
 * @author Igor Tikhonov <itikhonov83@gmail.com>
 */

require_once dirname(__FILE__) . '/lib/autoload.php';
require_once dirname(__FILE__) . '/vendor/autoload.php';

function showUsageExit()
{
    echo "Usage: php nightly.php --type=<default|custom> --alerts=<daily|weekly>";
    echo PHP_EOL;
    exit(1);
}

$opts = getopt(NULL, array('type:', 'alerts:'));
if (!isset($opts['type']) || !isset($opts['alerts'])) {
    showUsageExit();
}

$nightlySearch = new NightlySearch();
if ($opts['type'] == 'default' && $opts['alerts'] == 'daily') {
    $nightlySearch->isDefault = true;
    $nightlySearch->alerts = SavedSearch::ALERTS_DAILY;
} else if ($opts['type'] == 'default' && $opts['alerts'] == 'weekly') {
    $nightlySearch->isDefault = true;
    $nightlySearch->alerts = SavedSearch::ALERTS_WEEKLY;
} else if ($opts['type'] == 'custom' && $opts['alerts'] == 'daily') {
    $nightlySearch->isDefault = false;
    $nightlySearch->alerts = SavedSearch::ALERTS_DAILY;
} else if ($opts['type'] == 'custom' && $opts['alerts'] == 'weekly') {
    $nightlySearch->isDefault = false;
    $nightlySearch->alerts = SavedSearch::ALERTS_WEEKLY;
} else {
    showUsageExit();
}

$nightlySearch->run();