<?php
/**
 * Yet Another Dating Site based on socionics
 * PHP, Linux, Apache, Nginx, Twig, MongoDB, Redis, RabbitMQ, AJAX, JQuery, JSON
 *
 * @author Igor Tikhonov <itikhonov83@gmail.com>
 */

class Article
{
    public $title;
    public $link;
    public $author;
    public $date;
    public $language;
    public $paragraphs = [];

    public function loadByLink()
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->articles;
        $record = $collection->findOne(
            array(
                'link' => $this->link,
            ),
            array(
                'title' => true,
                'author' => true,
                'date' => true,
                'language' => true,
                'paragraphs' => true,
            ));
        $this->title = $record['title'];
        $this->author = $record['author'];
        $this->date = $record['date'];
        $this->date = date('d M Y', $this->date->sec);
        $this->language = $record['language'];
        $this->paragraphs = $record['paragraphs'];
    }

    public function store()
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->articles;
        $collection->insert(
            array(
                'title' => $this->title,
                'link' => $this->link,
                'author' => $this->author,
                'date' => new MongoDate(),
                'language' => $this->language,
                'paragraphs' => $this->paragraphs,
            ));
    }
}