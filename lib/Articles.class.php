<?php
/**
 * Yet Another Dating Site based on socionics
 * PHP, Linux, Apache, Nginx, Twig, MongoDB, Redis, RabbitMQ, AJAX, JQuery, JSON
 *
 * @author Igor Tikhonov <itikhonov83@gmail.com>
 */

class Articles {
    public static function isInDb($article) {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->articles;
        $record = $collection->findOne(
            array(
                'link' => $article->link,
                'language' => $article->language,
            ),
            array('_id' => true,
            ));

        if ($record === NULL) {
            return false;
        }

        return true;
    }

    public $language;

    /**
     * @var array
     */
    public $list = [];

    public function loadList() {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->articles;
        $cursor = $collection->find(
            array('language' => $this->language),
            array(
                'title' => true,
                'link' => true,
                'author' => true,
                'date' => true,
            )
        );
        foreach ($cursor as $document) {
            $article = new Article();
            $article->title = $document['title'];
            $article->link = $document['link'];
            $article->author = $document['author'];
            $article->date = $document['date'];
            $this->list[] = $article;
        }
    }
}