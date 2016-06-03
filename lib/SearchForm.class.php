<?php
/**
 * Yet Another Dating Site based on socionics
 * PHP, Linux, Apache, Nginx, Twig, MongoDB, Redis, RabbitMQ, AJAX, JQuery, JSON
 *
 * @author Igor Tikhonov <itikhonov83@gmail.com>
 */

class SearchForm extends Form
{
    /**
     * @var Search
     */
    public $search;

    /**
     * @return bool
     */
    public function validate()
    {
        if (!($this->search->gender == 1 || $this->search->gender == 2)) {
            return false;
        }

        if ($this->search->ageFrom < 18 || $this->search->ageTo > 70) {
            return false;
        }

        if ($this->search->country < 1) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function process()
    {
        $this->search = new SimpleSearch();
        $this->search->gender = (int)$_REQUEST['gender'];
        $this->search->ageFrom = (int)$_REQUEST['ageFrom'];
        $this->search->ageTo = (int)$_REQUEST['ageTo'];
        $this->search->country = (int)$_REQUEST['country'];

        if ($this->validate()) {
            $this->search->run();

            return true;
        }

        return false;
    }
}