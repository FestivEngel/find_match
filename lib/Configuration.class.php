<?php
/**
 * Yet Another Dating Site based on socionics
 * PHP, Linux, Apache, Nginx, Twig, MongoDB, Redis, RabbitMQ, AJAX, JQuery, JSON
 *
 * @author Igor Tikhonov <itikhonov83@gmail.com>
 */

// Time for test:
// > db.configuration.insert({"key": "timeForTest", "value": 900});
// Check for messages interval:
// > db.configuration.insert({"key": "checkForMessagesInterval", "value": 10});
// Email for complaints
// > db.configuration.insert({"key": "emailForComplaints", "value": "itikhonov83@gmail.com"});
// Email for purchases notifications
// > db.configuration.insert({"key": "emailForPurchaseNotification", "value": "itikhonov83@gmail.com"});
// Restore password token time to live in seconds
// > db.configuration.insert({"key": "restorePasswordTokenTTL", "value": 14400 });
class Configuration
{
    const PHOTOS_FOLDER = '/var/www/html/photos';
    const THUMB_PREFIX = 'thumb_';
    const IMAGE_MAX_SIZE = 500;
    const THUMB_MAX_SIZE = 150;

    private static $instance = null;

    /**
     * @return Configuration
     */
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new Configuration();
        }

        return self::$instance;
    }

    /**
     * @param string $fileName
     * @return string
     */
    public static function getPhotoFullFileName($fileName)
    {
        return Configuration::PHOTOS_FOLDER . '/' . $fileName;
    }

    /**
     * @param string $fileName
     * @return string
     */
    public static function getThumbFullFileName($fileName)
    {
        return Configuration::PHOTOS_FOLDER . '/' . Configuration::THUMB_PREFIX . $fileName;
    }

    /**
     * @var array
     */
    private $data = [];

    private function __construct()
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->configuration;
        $cursor = $collection->find();
        foreach ($cursor as $document) {
            $this->data[$document['key']] = $document['value'];
        }
    }

    public function __destruct()
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->configuration;
        foreach ($this->data as $key => $value) {
            $collection->update(
                array('key' => $key),
                array(
                    'key' => $key,
                    'value' => $value),
                array('upsert' => true)
            );
        }
    }

    /**
     * @param string $key
     * @param string $value
     */
    public function __set($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }

        return null;
    }
}