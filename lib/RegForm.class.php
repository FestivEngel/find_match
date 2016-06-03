<?php
/**
 * Yet Another Dating Site based on socionics
 * PHP, Linux, Apache, Nginx, Twig, MongoDB, Redis, RabbitMQ, AJAX, JQuery, JSON
 *
 * @author Igor Tikhonov <itikhonov83@gmail.com>
 */

class RegForm extends Form
{
    /**
     * @var Member
     */
    private $member;

    /**
     * @return bool
     */
    public function validate()
    {
        // Username validation
        if ($this->member->nickname == "") {
            return false;
        }

        // Email validation
        if (!Auth::checkEmail($this->member->email)) {
            return false;
        }

        // Password validation
        if (strlen($this->member->password) < 3) {
            return false; // Error: password must contain at least 3 characters!
        }

        if ($this->member->password == $this->member->nickname) {
            return false; // Error: password must be different from username!
        }

        return true;
    }

    /**
     * @return bool
     */
    public function process()
    {
        if ($_REQUEST['formToken'] == $_SESSION['formToken']) {
            $this->member = new Member();
            $this->member->authMethod = Auth::METHOD_REGFORM;
            $this->member->nickname = $_REQUEST['nickname'];
            $this->member->gender = (int)$_REQUEST['gender'];
            $dateTime = new DateTime();
            $dateTime->setTime(0, 0, 0);
            $dateTime->setDate($_REQUEST['bYear'], $_REQUEST['bMonth'], $_REQUEST['bDay']);
            $this->member->bDate = $dateTime->getTimestamp();
            $this->member->email = $_REQUEST['email'];
            $this->member->password = $_REQUEST['password'];
            $this->member->invitedBy = 0;
            $inviteKey = new InviteKey();
            if (isset($_REQUEST['inviteToken'])) {
                $inviteKey->token = $_REQUEST['inviteToken'];
            }
            $inviteKey->email = $_REQUEST['email'];
            if (isset($_REQUEST['inviteToken'])) {
                $invitedBy = $inviteKey->getUserIdByToken();
                if ($inviteKey) {
                    $this->member->invitedBy = $invitedBy;
                }
            }

            if ($this->member->invitedBy == 0) {
                $invitedBy = $inviteKey->getUserIdByEmail();
                if ($inviteKey) {
                    $this->member->invitedBy = $invitedBy;
                }
            }

            if ($this->validate() && !$this->member->isInDb()) {
                $this->member->lang = $_SESSION['lang'];
                $userId = $this->member->store();
                $savedSearch = new SavedSearch();
                $savedSearch->userId = $userId;
                $savedSearch->createDefault();
                $_SESSION['userId'] = $userId;

                return true;
            }
        }

        return false;
    }
}