<?php
/**
 * Yet Another Dating Site based on socionics
 * PHP, Linux, Apache, Nginx, Twig, MongoDB, Redis, RabbitMQ, AJAX, JQuery, JSON
 *
 * @author Igor Tikhonov <itikhonov83@gmail.com>
 */

class InviteKey
{
    /**
     * @var array
     */
    private $keys = array(
        'genDate',
        'userId',
        'email',
        'token',
    );

    /**
     * @var array
     */
    private $data = [];

    /**
     * @param int $min
     * @param int $max
     * @return mixed
     */
    private function crypto_rand_secure($min, $max)
    {
        $range = $max - $min;
        if ($range < 1) {
            return $min;
        } // not so random...

        $log = ceil(log($range, 2));
        $bytes = (int)($log / 8) + 1; // length in bytes
        $bits = (int)$log + 1; // length in bits
        $filter = (int)(1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd >= $range);

        return $min + $rnd;
    }

    /**
     * @param int $length
     * @return string
     */
    function getToken($length)
    {
        $token = "";
        $codeAlphabet = "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet .= "0123456789";
        $max = strlen($codeAlphabet) - 1;
        for ($i = 0; $i < $length; $i++) {
            $token .= $codeAlphabet[$this->crypto_rand_secure(0, $max)];
        }

        return $token;
    }

    public function __set($name, $value)
    {
        if (in_array($name, $this->keys)) {
            $this->data[$name] = $value;
        }
    }

    public function __get($name)
    {
        if (in_array($name, $this->keys)) {
            return $this->data[$name];
        }
    }

    public function store()
    {
        $this->data['token'] = $this->getToken(10);
        $this->data['genDate'] = new MongoDate();
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->invites;
        $collection->insert($this->data);
    }

    /**
     * @return string|null
     */
    public function getUserIdByEmail()
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->invites;
        $result = $collection->findOne(
            array(
                'email' => $this->data['email'],
            ),
            array(
                'userId' => true,
            )
        );

        if ($result) {
            return $result['userId'];
        }

        return null;
    }

    /**
     * @return string|null
     */
    public function getUserIdByToken()
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->invites;
        $result = $collection->findOne(
            array(
                'token' => $this->data['token'],
            ),
            array(
                'userId' => true,
            )
        );

        if ($result) {
            return $result['userId'];
        }

        return null;
    }
}