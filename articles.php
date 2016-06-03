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
$article = new Article();
$article->language = $core->args['lang'];
$showArticle = false;
if (isset($_REQUEST['what']) && ctype_alnum($_REQUEST['what'])) {
    $article->link = $_REQUEST['what'];
    if (Articles::isInDb($article)) {
        $showArticle = true;
    }
}

if ($showArticle) {
    $article->loadByLink();
    $core->args['article'] = $article;
    $core->renderArticle();
} else {
    $articles = new Articles();
    $articles->language = $core->args['lang'];
    $articles->loadList();
    $core->args['articles'] = $articles->list;
    $core->renderArticlesIndex();
}