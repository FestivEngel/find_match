<?php
/**
 * Yet Another Dating Site based on socionics
 * PHP, Linux, Apache, Nginx, Twig, MongoDB, Redis, RabbitMQ, AJAX, JQuery, JSON
 *
 * @author Igor Tikhonov <itikhonov83@gmail.com>
 */

class GoogleAuth extends Auth {
    const CLIENT_ID = '158052668214-k8cs2tof367re30lo0haetbmc57c457i.apps.googleusercontent.com';
    const CLIENT_SECRET = '78J4z0GYBRWLMKxa0UxVseDX';

    /**
     * @return string
     */
    public static function getAuthLink() {
        $params = array(
            'redirect_uri'  => $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/googleauth',
            'response_type' => 'code',
            'client_id'     => self::CLIENT_ID,
            'scope'         => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile'
        );

        $authLink = 'https://accounts.google.com/o/oauth2/auth' . '?' . urldecode(http_build_query($params));

        return $authLink;
    }

    /**
     * @var array $userInfo;
     */
    private $userInfo;

    /**
     * @param string $code
     */
    public function getUserInfo($code) {
        $params = array(
            'client_id' => self::CLIENT_ID,
            'client_secret' => self::CLIENT_SECRET,
            'redirect_uri' => $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/googleauth',
            'grant_type' => 'authorization_code',
            'code' => $code,
        );

        $url = 'https://accounts.google.com/o/oauth2/token';

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, urldecode(http_build_query($params)));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($curl);
        curl_close($curl);
        $tokenInfo = json_decode($result, true);

        if (isset($tokenInfo['access_token'])) {
            $params['access_token'] = $tokenInfo['access_token'];

            $userInfo = json_decode(file_get_contents('https://www.googleapis.com/oauth2/v1/userinfo' . '?' . urldecode(http_build_query($params))), true);
            if (isset($userInfo['id'])) {
                $this->userInfo = $userInfo;
                print_r($userInfo);
            }
        }
    }

    public function __get($name) {
        switch ($name) {
            case 'userName':
                return $this->userInfo['id'];

            case 'firstName':
                return $this->userInfo['given_name'];

            case 'lastName':
                return $this->userInfo['family_name'];

            case 'email':
                return $this->userInfo['email'];

            case 'photo':
                return $this->userInfo['picture'];

            case 'locale':
                return $this->userInfo['locale'];
        }
    }
}