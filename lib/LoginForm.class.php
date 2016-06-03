<?php
/**
 * Yet Another Dating Site based on socionics
 * PHP, Linux, Apache, Nginx, Twig, MongoDB, Redis, RabbitMQ, AJAX, JQuery, JSON
 *
 * @author Igor Tikhonov <itikhonov83@gmail.com>
 */

class LoginForm extends Form {
    private $member;

    /**
     * @return bool
     */
    public function validate() {
        // Email validation
        if (!Auth::checkEmail($this->member->email)) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function process() {
        $this->member = new Member();
        $this->member->email = $_REQUEST['email'];
        if ($this->validate()) {
            $this->member->load();
            if (Auth::comparePasswords($_REQUEST['password'], $this->member->password)) {
                $this->member->updateLastLoginTs();
                $_SESSION['userId'] = $this->member->id;

                return true;
            }
        }

        return false;
    }
}