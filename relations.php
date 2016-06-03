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
if (!isset($_SESSION['userId'])) {
    Core::redirect2Index();
}

switch ($_REQUEST['what']) {
    case 'ac':
    case 'cf':
    case 'cg':
    case 'cp':
    case 'du':
    case 'ex':
    case 'id':
    case 'mg':
    case 'mr':
    case 'qi':
    case 'rqminus':
    case 'rqplus':
    case 'sd':
    case 'se':
    case 'svminus':
    case 'svplus':
        $core->args['irType'] = $_REQUEST['what'];
        $core->renderRelationsIRTypes();
        break;

    case 'enfj':
    case 'enfp':
    case 'entj':
    case 'entp':
    case 'esfj':
    case 'esfp':
    case 'estj':
    case 'estp':
    case 'infj':
    case 'infp':
    case 'intj':
    case 'intp':
    case 'isfj':
    case 'isfp':
    case 'istj':
    case 'istp':
        $core->args['type'] = $_REQUEST['what'];
        $core->renderRelationsTypes();
        break;

    default:
        $member = new Member();
        $member->id = $_SESSION['userId'];
        $member->load();
        $intertypeRelations = RelationsMatrix::getIntertypeRelations($member->code);
        $intertypeRelations2 = [];
        foreach ($intertypeRelations as $key => $value) {
            $intertypeRelation = new IntertypeRelation();
            $intertypeRelation->socionicsCode = $key;
            $intertypeRelation->socionicsCodeName = RelationsMatrix::getSocionicsCodeName($key);
            $intertypeRelation->socionicsDescriptionLink = RelationsMatrix::getSocionicsDescriptionLink($key);
            $intertypeRelation->intertypeRelationCode = $value;
            $intertypeRelation->intertypeRelationCodeName = RelationsMatrix::getIntertypeRelationCodeName($value);
            $intertypeRelation->intertypeRelationDescriptionLink = RelationsMatrix::getIntertypeRelationDescriptionLink($value);
            $intertypeRelations2[] = $intertypeRelation;
        }

        $core->args['sociotype'] = $member->code;
        $core->args['intertypeRelations'] = $intertypeRelations2;
        $core->renderRelationsIndex();
}