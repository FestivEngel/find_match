<?php
/**
 * Yet Another Dating Site based on socionics
 * PHP, Linux, Apache, Nginx, Twig, MongoDB, Redis, RabbitMQ, AJAX, JQuery, JSON
 *
 * @author Igor Tikhonov <itikhonov83@gmail.com>
 */

abstract class Auth
{
    const METHOD_REGFORM = "REGFORM";
    const METHOD_VK = "VK";
    const METHOD_GOOGLE = "GOOGLE";
    const METHOD_FB = "FB";

    /**
     * @param string $password
     * @return string
     */
    public static function cryptPassword($password)
    {
        // A higher "cost" is more secure but consumes more processing power
        $cost = 10;

        // Create a random salt
        // Need to install php5-mcrypt module
        // $ sudo apt-get install php5-mcrypt
        // $ sudo ln -s /etc/php5/conf.d/mcrypt.ini /etc/php5/apache2/conf.d/   # for Apache
        // $ sudo ln -s /etc/php5/conf.d/mcrypt.ini /etc/php5/cli/conf.d/       # for CLI
        // $ sudo php5enmod mcrypt
        // $ sudo service apache2 restart
        $salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');

        // Prefix information about the hash so PHP knows how to verify it later.
        // "$2a$" Means we're using the Blowfish algorithm. The following two digits are the cost parameter.
        $salt = sprintf("$2a$%02d$", $cost) . $salt;

        // Hash the password with the salt
        $hash = crypt($password, $salt);

        return $hash;
    }

    /**
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public static function comparePasswords($password, $hash)
    {
        // For PHP with version < 5.6
        if (!function_exists('hash_equals')) {
            function hash_equals($str1, $str2)
            {
                if (strlen($str1) != strlen($str2)) {
                    return false;
                } else {
                    $res = $str1 ^ $str2;
                    $ret = 0;
                    for ($i = strlen($res) - 1; $i >= 0; $i--) $ret |= ord($res[$i]);
                    return !$ret;
                }
            }
        }

        if (hash_equals($hash, crypt($password, $hash))) {
            return true;
        }

        return false;
    }

    /**
     * @param string $email
     * @return bool
     */
    public static function checkEmail($email)
    {
        // Email validation
        $re = '/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/';
        if (!preg_match($re, $email)) {
            return false;
        }

        return true;
    }

    public static function remindPassword($member)
    {

    }

    /**
     * @param string $code
     */
    abstract protected function getUserInfo($code);
}