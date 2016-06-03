<?php
/**
 * Yet Another Dating Site based on socionics
 * PHP, Linux, Apache, Nginx, Twig, MongoDB, Redis, RabbitMQ, AJAX, JQuery, JSON
 *
 * @author Igor Tikhonov <itikhonov83@gmail.com>
 */

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class Mailer
{
    const FROM = 'robot@find-match.online';
    const FROM_NAME = 'Robot';

    const TYPE_REGISTER = 1;
    const TYPE_INVITE = 2;
    const TYPE_COMPLAINT = 3;
    const TYPE_PURCHASE = 4;
    const TYPE_REMINDPWD = 5;
    const TYPE_BYE = 6;
    const TYPE_DEFAULT_SEARCH = 7;
    const TYPE_CUSTOM_SEARCH = 8;
    const TYPE_LIKES = 9;
    const TYPE_VIEWS = 10;
    const TYPE_SEARCH_ITEM = 11;
    const TYPE_NEW_MESSAGES = 12;

    private static $messageTypeStr = array(
        Mailer::TYPE_REGISTER => 'Mailer::TYPE_REGISTER',
        Mailer::TYPE_INVITE => 'Mailer::TYPE_INVITE',
        Mailer::TYPE_COMPLAINT => 'Mailer::TYPE_COMPLAINT',
        Mailer::TYPE_PURCHASE => 'Mailer::TYPE_PURCHASE',
        Mailer::TYPE_REMINDPWD => 'Mailer::TYPE_REMINDPWD',
        Mailer::TYPE_BYE => 'Mailer::TYPE_BYE',
        Mailer::TYPE_DEFAULT_SEARCH => 'Mailer::TYPE_DEFAULT_SEARCH',
        Mailer::TYPE_CUSTOM_SEARCH => 'Mailer::TYPE_CUSTOM_SEARCH',
        Mailer::TYPE_LIKES => 'Mailer::TYPE_LIKES',
        Mailer::TYPE_VIEWS => 'Mailer::TYPE_VIEWS',
        Mailer::TYPE_SEARCH_ITEM => 'Mailer::TYPE_SEARCH_ITEM',
        Mailer::TYPE_NEW_MESSAGES => 'Mailer::TYPE_NEW_MESSAGES',
    );

    private static $templateFilename = array(
        Mailer::TYPE_REGISTER => 'welcome.html',
        Mailer::TYPE_INVITE => 'invitation.html',
        Mailer::TYPE_COMPLAINT => 'abuse_report.txt',
        Mailer::TYPE_PURCHASE => 'membership.html',
        Mailer::TYPE_REMINDPWD => 'password.html',
        Mailer::TYPE_BYE => 'bye.html',
        Mailer::TYPE_DEFAULT_SEARCH => 'default_search.html',
        Mailer::TYPE_CUSTOM_SEARCH => 'custom_search.html',
        Mailer::TYPE_LIKES => 'likes.html',
        Mailer::TYPE_VIEWS => 'views.html',
        Mailer::TYPE_SEARCH_ITEM => 'search_item.html',
        Mailer::TYPE_NEW_MESSAGES => 'new_messages.html',
    );

    /**
     * @var array
     */
    public $data = [];

    /**
     * @var string
     */
    public $serializedMsg;

    public function putInQueue()
    {
        $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();
        $channel->queue_declare('mails', false, false, false, false);
        if (!isset($this->data['lang'])) {
            $this->data['lang'] = 'en';
        }

        $msg = new AMQPMessage(serialize($this->data));
        $channel->basic_publish($msg, '', 'mails');
        $channel->close();
        $connection->close();
    }

    private function getTemplateFullFilename($type)
    {
        $filename = dirname(__FILE__) . '/../emails/';
        if ($this->data['lang'] == 'ru') {
            $filename .= 'ru_RU/';
        } else {
            $filename .= 'en_US/';
        }

        $filename .= Mailer::$templateFilename[$type];

        return $filename;
    }

    /**
     * @return string
     */
    private function prepareRegisterBody()
    {
        $body = file_get_contents($this->getTemplateFullFilename(Mailer::TYPE_REGISTER));
        $search = array('%userName%', '%userId%');
        $replace = array($this->data['userName'], $this->data['userId']);

        return str_replace($search, $replace, $body);
    }

    /**
     * @return string
     */
    private function prepareInviteBody()
    {
        $body = file_get_contents($this->getTemplateFullFilename(Mailer::TYPE_INVITE));

        return str_replace('%userName%', $this->data['userName'], $body);
    }

    /**
     * @return string
     */
    private function prepareComplaintBody()
    {
        $body = file_get_contents($this->getTemplateFullFilename(Mailer::TYPE_COMPLAINT));
        $search = array('%userName%', '%email%', '%userId%', '%anotherUserName%', '%anotherUserEmail%', '%anotherUserId%', '%complaint%');
        $replace = array(
            // Reporter
            $this->data['user1Nickname'],
            $this->data['user1Email'],
            $this->data['user1Id'],
            // Reported profile
            $this->data['user2Nickname'],
            $this->data['user2Email'],
            $this->data['user2Id'],
            // Message
            $this->data['message'],
        );

        return str_replace($search, $replace, $body);
    }

    /**
     * @return string
     */
    private function preparePurchaseBody()
    {
        $membership = $this->data['membership'];
        switch ($membership) {
            case Membership::MONTH1:
                $membership = _('paid 1 month membership');
                break;

            case Membership::MONTH3:
                $membership = _('paid 3 months membership');
                break;

            case Membership::MONTH6:
                $membership = _('paid 6 months membership');
                break;

            case Membership::LIFETIME:
                $membership = _('paid lifetime membership');
                break;
        }

        $body = file_get_contents($this->getTemplateFullFilename(Mailer::TYPE_PURCHASE));
        $search = array('%userName%', '%membership%', '%userId%');
        $replace = array($this->data['userName'], $membership, $this->data['userId']);

        return str_replace($search, $replace, $body);
    }

    /**
     * @return string
     */
    private function prepareRemindPwdBody()
    {
        $body = file_get_contents($this->getTemplateFullFilename(Mailer::TYPE_REMINDPWD));

        return str_replace('%token%', $this->data['token'], $body);
    }

    /**
     * @return string
     */
    private function prepareByeBody()
    {
        $body = file_get_contents($this->getTemplateFullFilename(Mailer::TYPE_BYE));

        return $body;
    }

    private function prepareSearchItems()
    {
        $body = '';
        $item = file_get_contents($this->getTemplateFullFilename(Mailer::TYPE_SEARCH_ITEM));
        foreach ($this->data['foundMembers'] as $foundMember) {
            $search = array('%anotherUserId%', '%anotherUserPhoto%');
            $replace = array($foundMember->id, Member::getBase64EncodedImage($foundMember->id));
            $body .= str_replace($search, $replace, $item);
        }

        return $body;
    }

    /**
     * @return string
     */
    private function prepareDefaultSearchBody()
    {
        $body = file_get_contents($this->getTemplateFullFilename(Mailer::TYPE_DEFAULT_SEARCH));
        $items = $this->prepareSearchItems();
        $search = array('%searchItems%', '%userId%');
        $replace = array($this->data['searchName'], $items, $this->data['userId']);

        return str_replace($search, $replace, $body);
    }

    /**
     * @return string
     */
    private function prepareCustomSearchBody()
    {
        $body = file_get_contents($this->getTemplateFullFilename(Mailer::TYPE_CUSTOM_SEARCH));
        $items = $this->prepareSearchItems();
        $search = array('%searchName%', '%searchItems%', '%userId%');
        $replace = array($this->data['searchName'], $items, $this->data['userId']);

        return str_replace($search, $replace, $body);
    }

    /**
     * @return string
     */
    private function prepareLikesBody()
    {
        $body = file_get_contents($this->getTemplateFullFilename(Mailer::TYPE_LIKES));
        $search = array('%anotherUserName%', '%anotherUserId%', '%anotherUserPhoto%', '%userId%');
        $replace = array($this->data['anotherUserName'], $this->data['anotherUserId'],
            Member::getBase64EncodedImage($this->data['anotherUserId']),
            $this->data['userId']);

        return str_replace($search, $replace, $body);
    }

    /**
     * @return string
     */
    private function prepareViewsBody()
    {
        $body = file_get_contents($this->getTemplateFullFilename(Mailer::TYPE_VIEWS));
        $search = array('%anotherUserName%', '%anotherUserId%', '%anotherUserPhoto%', '%userId%');
        $replace = array($this->data['anotherUserName'], $this->data['anotherUserId'],
            Member::getBase64EncodedImage($this->data['anotherUserId']),
            $this->data['userId']);

        return str_replace($search, $replace, $body);
    }

    public function prepareNewMessagesBody()
    {
        return '';
    }

    public function send()
    {
        $this->data = unserialize($this->serializedMsg);
        $mail = new PHPMailer();
        $mail->CharSet = "UTF-8";
        $mail->isSMTP(); // Set mailer to use SMTP
        $mail->Host = '127.0.0.1'; // Specify main and backup SMTP servers
        $mail->SMTPAuth = false;
        $mail->setFrom(Mailer::FROM, Mailer::FROM_NAME);
        $mail->addAddress($this->data['email']);
        if ($this->data['type'] != Mailer::TYPE_COMPLAINT) {
            $mail->isHTML(true);
        } else {
            $mail->isHTML(false);
        }

        // Locale
        switch ($this->data['lang']) {
            case 'ru':
                $lang = 'ru_RU';
                $codeset = 'utf8';
                $locale = $lang . '.' . $codeset;
                break;

            default:
                $lang = 'en_US';
                $codeset = 'utf8';
                $locale = $lang . '.' . $codeset;
                break;
        }

        setlocale(LC_ALL, $locale);
        bindtextdomain('messages', dirname(__FILE__) . '/../locale');
        textdomain('messages');
        bind_textdomain_codeset('messages', 'UTF-8');

        switch ($this->data['type']) {
            case Mailer::TYPE_REGISTER:
                $mail->Subject = _('Welcome to Find Match!');
                $mail->Body = $this->prepareRegisterBody();
                break;

            case Mailer::TYPE_INVITE:
                $mail->Subject = $this->data['userName'] . ' ' . _('invited you to Find Match');
                $mail->Body = $this->prepareInviteBody();
                break;

            case Mailer::TYPE_COMPLAINT:
                $mail->Subject = _('Abuse report');
                $mail->Body = $this->prepareComplaintBody();
                break;

            case Mailer::TYPE_PURCHASE:
                $mail->Subject = _('Membership change on Find Match');
                $mail->Body = $this->preparePurchaseBody();
                break;

            case Mailer::TYPE_REMINDPWD:
                $mail->Subject = _('Password restore on Find Match!');
                $mail->Body = $this->prepareRemindPwdBody();
                break;

            case Mailer::TYPE_BYE:
                $mail->Subject = _('We are sorry to see you leaving Find Match');
                $mail->Body = $this->prepareByeBody();
                break;

            case Mailer::TYPE_DEFAULT_SEARCH:
                $mail->Subject = _('You have new matches on Find Match');
                $mail->Body = $this->prepareDefaultSearchBody();
                break;

            case Mailer::TYPE_CUSTOM_SEARCH:
                $mail->Subject = _('You have new matches on Find Match');
                $mail->Body = $this->prepareCustomSearchBody();
                break;

            case Mailer::TYPE_LIKES:
                $mail->Subject = _('You have a new admirer on Find Match');
                $mail->Body = $this->prepareLikesBody();
                break;

            case Mailer::TYPE_VIEWS:
                $mail->Subject = _('Your profile has been viewed on Find Match');
                $mail->Body = $this->prepareViewsBody();
                break;

            case Mailer::TYPE_NEW_MESSAGES:
                $mail->Subject = _('You have new messages on Find Match');
                $mail->Body = $this->prepareNewMessagesBody();
                break;
        }

        if (!$mail->send()) {
            echo 'Message (type = ';
            echo Mailer::$messageTypeStr[$this->data['type']];
            echo ', to = ';
            echo $this->data['email'];
            echo ', lang = ';
            echo $this->data['lang'];
            echo ') could not be sent. ';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
            echo PHP_EOL;
        } else {
            echo 'Message (type = ';
            echo Mailer::$messageTypeStr[$this->data['type']];
            echo ', to = ';
            echo $this->data['email'];
            echo ', lang = ';
            echo $this->data['lang'];
            echo ') has been sent';
            echo PHP_EOL;
        }
    }
}