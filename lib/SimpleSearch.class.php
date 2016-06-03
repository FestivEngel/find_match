<?php
/**
 * Yet Another Dating Site based on socionics
 * PHP, Linux, Apache, Nginx, Twig, MongoDB, Redis, RabbitMQ, AJAX, JQuery, JSON
 *
 * @author Igor Tikhonov <itikhonov83@gmail.com>
 */

class SimpleSearch extends Search
{
    public $gender;
    public $ageFrom;
    public $ageTo;
    public $country;

    public $foundMembers = [];

    public function run()
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->members;
        $query = array(
            'active' => true,
            'gender' => $this->gender,
            //'testTaken' => true,
            'bDate' => array(
                '$gte' => Member::age2BYearStart($this->ageTo),
                '$lte' => Member::age2BYearEnd($this->ageFrom),
            ),
            'livingCountry' => $this->country,
        );
        //var_dump($query);
        $cursor = $collection->find($query,
            array(
                '_id' => true,
                'lastLoginTs' => true,
                'authMethod' => true,
                'hasPhoto' => true,
                'photoFileName' => true,
                'nickname' => true,
                'bDate' => true,
                'sociotype' => true,
                'livingCity' => true,
                'livingState' => true,
                'livingCountry' => true,
            ))->limit(100);
        $cursor->sort(array('lastLoginTs' => -1));
        if (isset($_SESSION['userId'])) {
            $currentUserSociotype = Member::getSociotypeById($_SESSION['userId']);
        }

        $i = 0;
        $j = 0;
        foreach ($cursor as $doc) {
            $member = new Member();
            $member->id = $doc['_id'];
            $member->authMethod = $doc['authMethod'];
            $member->hasPhoto = $doc['hasPhoto'];
            $member->photoFileName = $doc['photoFileName'];
            $member->sociotype = $doc['sociotype'];
            $member->nickname = $doc['nickname'];
            $member->bDate = $doc['bDate'];
            $member->livingCity = $doc['livingCity'];
            $member->livingState = $doc['livingState'];
            $member->livingCountry = $doc['livingCountry'];
            $member->inFavorites = Favorite::isFavorite($_SESSION['userId'], $member->id);
            $member->viewed = ProfileView::isViewed($_SESSION['userId'], $member->id);
            $member->mutualLike = Like::isMutuallyLiked($_SESSION['userId'], $member->id);
            if (isset($currentUserSociotype)) {
                $member->relations = RelationsMatrix::getIntertypeRelationCodeName(RelationsMatrix::getIntertypeRelation($currentUserSociotype, $member->sociotype));
            }

            $this->foundMembers[$j][$i] = $member;
            $i++;
            if ($i == 4) {
                $i = 0;
                $j++;
            }
        }
    }
}