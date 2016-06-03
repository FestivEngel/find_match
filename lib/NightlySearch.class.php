<?php
/**
 * Yet Another Dating Site based on socionics
 * PHP, Linux, Apache, Nginx, Twig, MongoDB, Redis, RabbitMQ, AJAX, JQuery, JSON
 *
 * @author Igor Tikhonov <itikhonov83@gmail.com>
 */

class NightlySearch extends Search
{
    /**
     * @var bool
     */
    public $isDefault;

    /**
     * @var int
     */
    public $alerts;

    public function run()
    {
        $lastRunTs = new MongoDate($this->alerts == SavedSearch::ALERTS_DAILY ? time() - 24 * 60 * 60 : time() - 7 * 24 * 60 * 60);
        echo 'Running nightly search (isDefault = ';
        echo $this->isDefault ? 'true' : 'false';
        echo ', alerts = ';
        echo SavedSearch::$alertStr[$this->alerts];
        echo ', lastRunTs <= ';
        echo date('Y-m-d H:i:s', $lastRunTs->sec);
        echo ')', PHP_EOL;
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->searches;
        $cursor = $collection->find(
            array(
                'isDefault' => $this->isDefault,
                'saved' => true,
                'alerts' => $this->alerts,
                'lastRunTs' => array('$lte' => $lastRunTs),
            )
        );
        echo 'Found: ';
        echo $cursor->count();
        echo ' search(es)';
        echo PHP_EOL;
        foreach ($cursor as $doc) {
            $savedSearch = new SavedSearch();
            $savedSearch->data = $doc;
            $advancedSearch = new AdvancedSearch();
            $advancedSearch->userId = $doc['userId'];
            $advancedSearch->dim2 = false;
            $advancedSearch->checkRegTs = true;
            $advancedSearch->regTs = $lastRunTs;
            $advancedSearch->savedSearch = $savedSearch;
            $advancedSearch->run();
            if ($advancedSearch->getFoundMembersCount() == 0) {
                continue;
            }

            if ($this->isDefault) {
                echo 'Default search';
            } else {
                echo 'Search named ', $doc['name'];
            }

            echo ' found ', $advancedSearch->getFoundMembersCount(), ' new match(es):';
            echo PHP_EOL;
            $mailer = new Mailer();
            if ($this->isDefault) {
                $mailer->data['type'] = Mailer::TYPE_DEFAULT_SEARCH;
            } else {
                $mailer->data['type'] = Mailer::TYPE_CUSTOM_SEARCH;
                $mailer->data['searchName'] = $savedSearch->name;
            }

            $mailer->data['email'] = Member::getEmailById($doc['userId']);
            $mailer->data['lang'] = Member::getLangById($doc['userId']);
            $mailer->data['userId'] = $doc['userId'];
            $mailer->data['userName'] = Member::getNicknameById($doc['userId']);
            $i = 1;
            foreach ($advancedSearch->foundMembers as $doc2) {
                $foundMember = new FoundMember();
                $foundMember->id = $doc2->id;
                $foundMember->nickname = $doc2->nickname;
                $foundMember->hasPhoto = $doc2->hasPhoto;
                $foundMember->photoFileName = $doc2->photoFileName;
                echo $i , ': nickname = ', $foundMember->nickname;
                echo ', id = ', $foundMember->id;
                echo PHP_EOL;
                $i++;

                $mailer->data['foundMembers'][] = $foundMember;
            }

            $mailer->putInQueue();
            $savedSearch->updateLastRunTs();
        }
    }
}