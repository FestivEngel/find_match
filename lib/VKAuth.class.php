<?php
/**
 * Yet Another Dating Site based on socionics
 * PHP, Linux, Apache, Nginx, Twig, MongoDB, Redis, RabbitMQ, AJAX, JQuery, JSON
 *
 * @author Igor Tikhonov <itikhonov83@gmail.com>
 */

class VKAuth extends Auth
{
    const CLIENT_ID = '5402781';
    const CLIENT_SECRET = '7jzRcNQfoVZbNGuUtQu9';

    /**
     * @return string
     */
    public static function getAuthLink()
    {
        $params = array(
            'client_id' => self::CLIENT_ID, // Client ID from VK
            'redirect_uri' => $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/vkauth',
            'response_type' => 'code',
            'scope' => 'email',
        );

        $authLink = 'http://oauth.vk.com/authorize' . '?' . urldecode(http_build_query($params));

        return $authLink;
    }

    /**
     * @var array $userInfo ;
     */
    private $userInfo;

    /**
     * @var bool
     */
    public $hasEmail = false;

    /**
     * @var string
     */
    public $email;

    /**
     * @param string $code
     */
    public function getUserInfo($code)
    {
        $params = array(
            'client_id' => self::CLIENT_ID,
            'client_secret' => self::CLIENT_SECRET,
            'code' => $code,
            'redirect_uri' => $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/vkauth',
        );

        $url = 'https://oauth.vk.com/access_token' . '?' . urldecode(http_build_query($params));
        $token = json_decode(file_get_contents($url), true);
        if (isset($token['email'])) {
            $this->hasEmail = true;
            $this->email = $token['email'];
        }

        if (isset($token['access_token'])) {
            $params = array(
                'uids' => $token['user_id'],
                'fields' => 'uid,first_name,last_name,screen_name,sex,bdate,photo_big,city,country',
                'access_token' => $token['access_token'],
            );

            $userInfo = json_decode(file_get_contents('https://api.vk.com/method/users.get' . '?' . urldecode(http_build_query($params))), true);
            if (isset($userInfo['response'][0]['uid'])) {
                $this->userInfo = $userInfo['response'][0];
            }
        };
    }

    /**
     * @param string $name
     */
    public function __get($name)
    {
        switch ($name) {
            case 'userName':
                return $this->userInfo['screen_name'];

            case 'firstName':
                return $this->userInfo['first_name'];

            case 'lastName':
                return $this->userInfo['last_name'];

            // Gender: 1 - female, 2 - male
            case 'gender':
                if ($this->userInfo['sex'] == '1') {
                    return 'female';
                } else {
                    return 'male';
                }

            case 'bDate':
                return $this->userInfo['bdate'];

            case 'photo':
                return $this->userInfo['photo_big'];

            case 'city':
                return $this->userInfo['city'];

            case 'country':
                return $this->userInfo['country'];
        }

        return null;
    }
}