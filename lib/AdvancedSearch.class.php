<?php
/**
 * Yet Another Dating Site based on socionics
 * PHP, Linux, Apache, Nginx, Twig, MongoDB, Redis, RabbitMQ, AJAX, JQuery, JSON
 *
 * @author Igor Tikhonov <itikhonov83@gmail.com>
 */

class AdvancedSearch extends Search
{
    /**
     * @var SavedSearch
     */
    public $savedSearch;

    /**
     * @var Member array
     */
    public $foundMembers = [];

    /**
     * @var MongoDate
     */
    public $regTs;

    /**
     * @var bool
     */
    public $checkRegTs = false;

    /**
     * @var int
     */
    private $foundMembersCount = 0;

    /**
     * @var MongoId|string
     */
    public $userId;

    /**
     * @var bool
     */
    public $dim2 = true;

    /**
     * @var Member
     */
    private $me;

    public function __construct()
    {
        if (isset($_SESSION['userId'])) {
            $this->userId = $_SESSION['userId'];
        }
    }

    /**
     * @return int
     */
    public function getFoundMembersCount()
    {
        return $this->foundMembersCount;
    }

    public function run()
    {
        $query = array(
            '_id' => array(
                '$ne' => $this->userId instanceof MongoId ? $this->userId : new MongoId($this->userId),
            ),
            'active' => true,
            'profileFilled' => true,
        );

        if ($this->checkRegTs) {
            $query['regTs'] = array('$gte' => $this->regTs);
        }

        // sociotype
        if (!$this->savedSearch->isDefault) {
            $s = $this->savedSearch->pSociotype;
            if ($this->savedSearch->onlyDualSociotypes) {
                $ms = Member::getSociotypeById($this->userId);
                if ($ms) {
                    $query['sociotype'] = RelationsMatrix::getDualRelationCode(Member::getSociotypeById($this->userId));
                }
            } else {
                if ($s) {
                    $query['sociotype'] = $s;
                }
            }
        } else {
            if ($this->savedSearch->pRelationsType) {
                $ms = Member::getSociotypeById($this->userId);
                if ($ms) {
                    $s = RelationsMatrix::getSocionicsNums4RelationsType($ms, $this->savedSearch->pRelationsType);
                    $query['sociotype'] = array('$in' => $s);
                }
            }
        }

        // hasPhoto
        if ($this->savedSearch->onlyProfilesWithPhoto) {
            $query['hasPhoto'] = $this->savedSearch->onlyProfilesWithPhoto;
        }

        // age
        $af = $this->savedSearch->pAgeFrom;
        $at = $this->savedSearch->pAgeTo;
        if ($af && $at) {
            $query['bDate'] = array(
                '$gte' => Member::age2BYearStart($at),
                '$lte' => Member::age2BYearEnd($af),
            );
        } else if ($af) {
            $query['bDate'] = array('$lte' => Member::age2BYearEnd($af));
        } else if ($at) {
            $query['bDate'] = array('$gte' => Member::age2BYearStart($at));
        }

        // gender
        $g = $this->savedSearch->pGender;
        if ($g) {
            $query['gender'] = $g;
        }

        // livingCity
        $lc = $this->savedSearch->pLivingCity;
        if ($lc) {
            $query['livingCity'] = $lc;
        }

        // livingState
        $ls = $this->savedSearch->pLivingState;
        if ($ls) {
            $query['livingState'] = $ls;
        }

        // livingCountry
        $lc = $this->savedSearch->pLivingCountry;
        if ($lc) {
            $query['livingCountry'] = $lc;
        }

        // height
        $hf = $this->savedSearch->pHeightFrom;
        $ht = $this->savedSearch->pHeightTo;
        if ($hf && $ht) {
            $query['height'] = $hf == $ht ? $hf : array('$gte' => $hf, '$lte' => $ht);
        } else if ($hf) {
            $query['height'] = array('$gte' => $hf);
        } else if ($ht) {
            $query['height'] = array('$lte' => $ht);
        }

        // hairColor
        $hk = $this->savedSearch->pHairColor;
        if ($hk[0]) {
            $query['hairColor'] = array('$in' => $hk);
        }

        // hairLength
        $hl = $this->savedSearch->pHairLength;
        if ($hl[0]) {
            $query['hairLength'] = array('$in' => $hl);
        }

        // hairType
        $ht = $this->savedSearch->pHairType;
        if ($ht[0]) {
            $query['hairType'] = array('$in' => $ht);
        }

        // eyeColor
        $ec = $this->savedSearch->pEyeColor;
        if ($ec[0]) {
            $query['eyeColor'] = array('$in' => $ec);
        }

        // eyeWear
        $ew = $this->savedSearch->pEyeWear;
        if ($ew[0]) {
            $query['eyeWear'] = array('$in' => $ew);
        }

        // weight
        $wf = (int)$this->savedSearch->pWeightFrom;
        $wt = (int)$this->savedSearch->pWeightTo;
        if ($wf && $wt) {
            $query['weight'] = $wf == $wt ? $wf : array('$gte' => $wf, '$lte' => $wt);
        } else if ($wf) {
            $query['weight'] = array('$gte' => $wf);
        } else if ($wt) {
            $query['weight'] = array('$lte' => $wt);
        }

        // BMI
        $bf = (int)$this->savedSearch->pBMIFrom;
        $bt = (int)$this->savedSearch->pBMITo;
        if ($bf && $bt) {
            $query['BMI'] = $bf == $bt ? $wf : array('$gte' => $bf, '$lte' => $bt);
        } else if ($bf) {
            $query['BMI'] = array('$gte' => $bf);
        } else if ($bt) {
            $query['BMI'] = array('$lte' => $bt);
        }

        // bodyType
        $bt = $this->savedSearch->pBodyType;
        if ($bt[0]) {
            $query['bodyType'] = array('$in' => $bt);
        }

        // ethnicity
        $e = $this->savedSearch->pEthnicity;
        if ($e[0]) {
            $query['ethnicity'] = array('$in' => $e);
        }

        // bodyArt
        $ba = $this->savedSearch->pBodyArt;
        if ($ba[0]) {
            $query['bodyArt'] = array('$in' => $ba);
        }

        // drinking
        $d = $this->savedSearch->pDrinking;
        if ($d[0]) {
            $query['drinking'] = array('$in' => $d);
        }

        // smoking
        $s = $this->savedSearch->pSmoking;
        if ($s[0]) {
            $query['smoking'] = array('$in' => $s);
        }

        // maritalStatus
        $ms = $this->savedSearch->pMaritalStatus;
        if ($ms[0]) {
            $query['maritalStatus'] = array('$in' => $ms);
        }

        // kids
        $k = $this->savedSearch->pKids;
        if ($k[0]) {
            $query['kids'] = array('$in' => $k);
        }

        // numberOfKids
        $nk = $this->savedSearch->pNumberOfKids;
        if ($k[0] != 0 && $k[0] != 1) {
            $query['numberOfKids'] = array('$lte' => $nk);
        }

        // wantMoreKids
        $wmk = $this->savedSearch->pWantMoreKids;
        if ($wmk[0]) {
            $query['wantMoreKids'] = array('$in' => $wmk);
        }

        // employmentStatus
        $es = $this->savedSearch->pEmploymentStatus;
        if ($es[0]) {
            $query['employmentStatus'] = array('$in' => $es);
        }

        // field
        $f = $this->savedSearch->pField;
        if ($f[0]) {
            $query['field'] = array('$in' => $f);
        }

        // annualIncome
        $ai = $this->savedSearch->pAnnualIncome;
        if ($ai[0]) {
            $query['annualIncome'] = array('$in' => $ai);
        }

        // residence
        $r = $this->savedSearch->pResidence;
        if ($r[0]) {
            $query['residence'] = array('$in' => $r);
        }

        // religion
        $r = $this->savedSearch->pReligion;
        if ($r[0]) {
            $query['religion'] = array('$in' => $r);
        }

        // education
        $e = $this->savedSearch->pEducation;
        $query['education'] = array('$gte' => $e);

        // willing2Relocate
        $w2r = $this->savedSearch->pWilling2Relocate;
        if ($w2r[0]) {
            $query['willing2Relocate'] = array('$in' => $w2r);
        }

        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->members;
        $cursor = $collection->find($query, array(
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
        $currentUserSociotype = Member::getSociotypeById($this->userId);
        $favoritesList = new FavoritesList();
        $favoritesList->userId = $this->userId;
        $profileViewsList = new ProfileViewsList();
        $profileViewsList->userId = $this->userId;
        if ($cursor->count()) {
            if ($this->savedSearch->excludeViewed || $this->savedSearch->sortBy == SavedSearch::SORTBY_UnviewedFirst) {
                $profileViewsList->loadAnotherUsersIds();
            }

            if ($this->savedSearch->excludeFavorites) {
                $favoritesList->loadAnotherUsersIds();
            }
        }

        switch ($this->savedSearch->sortBy) {
            case SavedSearch::SORTBY_LastActivityDate:
                $cursor->sort(array('lastLoginTs' => -1));
                break;

            case SavedSearch::SORTBY_WithPhotoFirst:
                $cursor->sort(array('hasPhoto' => 1));
                break;

            case SavedSearch::SORTBY_Age:
                $cursor->sort(array('bDate' => -1));
                break;
        }

        $found = [];
        foreach ($cursor as $doc) {
            if ($cursor->count()) {
                if ($this->savedSearch->excludeViewed && $profileViewsList->isProfileViewed($doc['_id'])) {
                    continue;
                }

                if ($this->savedSearch->excludeFavorites && $favoritesList->isInFavorites($doc['_id'])) {
                    continue;
                }
            }

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
            $member->inFavorites = Favorite::isFavorite($this->userId, $member->id);
            $member->viewed = ProfileView::isViewed($this->userId, $member->id);
            $member->mutualLike = Like::isMutuallyLiked($this->userId, $member->id);
            if ($currentUserSociotype != ProfileSubstitutions::ANY) {
                $member->relations =
                    RelationsMatrix::getIntertypeRelationCodeName(RelationsMatrix::getIntertypeRelation($currentUserSociotype,
                        $member->sociotype));
            }

            $found[] = $member;
        }

        /**
         * @param Member $a
         * @param Member $b
         * @return bool
         */
        $cmp1 = function ($a, $b) use ($currentUserSociotype) {
            $val1 = RelationsMatrix::getIntertypeRelationWeight($currentUserSociotype, $a->sociotype);
            $val2 = RelationsMatrix::getIntertypeRelationWeight($currentUserSociotype, $b->sociotype);

            return $val1 < $val2;
        };

        /**
         * @param Member $a
         * @param Member $b
         * @return bool
         */
        $cmp2 = function ($a, $b) use ($profileViewsList) {
            $val1 = $profileViewsList->isProfileViewed($a->id);

            return $val1;
        };

        if ($this->savedSearch->sortBy == SavedSearch::SORTBY_Сompatibility &&
            !$this->savedSearch->onlyDualSociotypes &&
            $this->savedSearch->pSociotype == ProfileSubstitutions::ANY
        ) {
            usort($found, $cmp1);
        } else if ($this->savedSearch->sortBy == SavedSearch::SORTBY_UnviewedFirst) {
            usort($found, $cmp2);
        }

        if ($this->savedSearch->onlyReverseMatch) {
            $this->me = new Member();
            $this->me->load();
            foreach ($found as $value) {
                if ($this->check4ReverseMatch($value->id)) {
                    $value->exclude = true;
                }
            }
        }

        $i = 0;
        $j = 0;
        foreach ($found as $value) {
            if ($value->exclude) {
                continue;
            }

            if ($this->dim2) {
                $this->foundMembers[$j][$i] = $value;
                $this->foundMembersCount++;
                $i++;
                if ($i == 4) {
                    $i = 0;
                    $j++;
                }
            } else {
                $this->foundMembers[] = $value;
                $this->foundMembersCount++;
            }
        }

        $this->savedSearch->updateLastRunTs();
    }

    /**
     * @param MongoId|string $anotherUserId
     * @return bool
     */
    private function check4ReverseMatch($anotherUserId)
    {
        $savedSearch = new SavedSearch();
        $savedSearch->userId = $anotherUserId;
        $savedSearch->loadDefault();
        if ($savedSearch->onlyProfilesWithPhoto != $this->me->hasPhoto) {
            return false;
        }

        // pGender
        if ($savedSearch->pGender != ProfileSubstitutions::ANY &&
            $savedSearch->pGender != $this->me->gender
        ) {
            return false;
        }

        // pAgeFrom
        if ($savedSearch->pAgeFrom != ProfileSubstitutions::ANY &&
            $this->me->age < $savedSearch->pAgeFrom
        ) {
            return false;
        }

        // pAgeTo
        if ($savedSearch->pAgeTo != ProfileSubstitutions::ANY &&
            $this->me->age > $savedSearch->pAgeTo
        ) {
            return false;
        }

        // pLivingCity
        if ($savedSearch->pLivingCity != ProfileSubstitutions::ANY &&
            $savedSearch->pLivingCity != $this->me->livingCity
        ) {
            return false;
        }

        // pLivingState
        if ($savedSearch->pLivingState != ProfileSubstitutions::ANY &&
            $savedSearch->pLivingState != $this->me->livingState
        ) {
            return false;
        }

        // pLivingCountry
        if ($savedSearch->pLivingCountry != ProfileSubstitutions::ANY &&
            $savedSearch->pLivingCountry != $this->me->livingCountry
        ) {
            return false;
        }

        // pSociotype
        if ($savedSearch->onlyDualSociotypes) {
            if ($savedSearch->pSociotype != RelationsMatrix::getDualRelationCode($this->me->sociotype)) {
                return false;
            }
        } else {
            if ($savedSearch->pSociotype != $this->me->sociotype) {
                return false;
            }
        }

        // pHeightFrom
        if ($savedSearch->pHeightFrom != ProfileSubstitutions::ANY &&
            $this->me->height < $savedSearch->pHeightFrom
        ) {
            return false;
        }

        // pHeightTo
        if ($savedSearch->pHeightTo != ProfileSubstitutions::ANY &&
            $this->me->height > $savedSearch->pHeightTo
        ) {
            return false;
        }

        // pWeightFrom
        if ($savedSearch->pWeightFrom != ProfileSubstitutions::ANY &&
            $this->me->weight < $savedSearch->pWeightFrom
        ) {
            return false;
        }

        // pWeightTo
        if ($savedSearch->pWeightTo != ProfileSubstitutions::ANY &&
            $this->me->weight > $savedSearch->pWeightTo
        ) {
            return false;
        }

        // pBMIFrom
        if ($savedSearch->pBMIFrom != ProfileSubstitutions::ANY &&
            $this->me->BMI < $savedSearch->pBMIFrom
        ) {
            return false;
        }

        // pBMITo
        if ($savedSearch->pBMITo != ProfileSubstitutions::ANY &&
            $this->me->BMI > $savedSearch->pBMITo
        ) {
            return false;
        }

        // pNumberOfKids
        if ($savedSearch->pKids[1] != ProfileSubstitutions::NO &&
            $this->me->numberOfKids > $savedSearch->pNumberOfKids
        ) {
            return false;
        }

        // pEducation
        if ($savedSearch->pEducation != ProfileSubstitutions::ANY &&
            $this->me->education < $savedSearch->pEducation
        ) {
            return false;
        }

        // pHairColor
        if ($savedSearch->pHairColor[0] != ProfileSubstitutions::ANY &&
            !in_array($this->me->hairColor, $savedSearch->pHairColor)
        ) {
            return false;
        }

        // pHairLength
        if ($savedSearch->pHairLength[0] != ProfileSubstitutions::ANY &&
            !in_array($this->me->hairLength, $savedSearch->pHairLength)
        ) {
            return false;
        }

        // pHairType
        if ($savedSearch->pHairType[0] != ProfileSubstitutions::ANY &&
            !in_array($this->me->hairType, $savedSearch->pHairType)
        ) {
            return false;
        }

        // pEyeColor
        if ($savedSearch->pEyeColor[0] != ProfileSubstitutions::ANY &&
            !in_array($this->me->eyeColor, $savedSearch->pEyeColor)
        ) {
            return false;
        }

        // pEyeWear
        if ($savedSearch->pEyeWear[0] != ProfileSubstitutions::ANY &&
            !in_array($this->me->eyeWear, $savedSearch->pEyeWear)
        ) {
            return false;
        }
        // pBodyType
        if ($savedSearch->pBodyType[0] != ProfileSubstitutions::ANY &&
            !in_array($this->me->bodyType, $savedSearch->pBodyType)
        ) {
            return false;
        }

        // pEthnicity
        if ($savedSearch->pEthnicity[0] != ProfileSubstitutions::ANY &&
            !in_array($this->me->ethnicity, $savedSearch->pEthnicity)
        ) {
            return false;
        }

        // pBodyArt
        if ($savedSearch->pBodyArt[0] != ProfileSubstitutions::ANY &&
            !in_array($this->me->bodyArt, $savedSearch->pBodyArt)
        ) {
            return false;
        }

        // pDrinking
        if ($savedSearch->pDrinking[0] != ProfileSubstitutions::ANY &&
            !in_array($this->me->drinking, $savedSearch->pDrinking)
        ) {
            return false;
        }

        // pSmoking
        if ($savedSearch->pSmoking[0] != ProfileSubstitutions::ANY &&
            !in_array($this->me->smoking, $savedSearch->pSmoking)
        ) {
            return false;
        }

        // pMaritalStatus
        if ($savedSearch->pMaritalStatus[0] != ProfileSubstitutions::ANY &&
            !in_array($this->me->maritalStatus, $savedSearch->pMaritalStatus)
        ) {
            return false;
        }

        // pKids
        if ($savedSearch->pKids[0] != ProfileSubstitutions::ANY &&
            !in_array($this->me->kids, $savedSearch->pKids)
        ) {
            return false;
        }

        // pWantMoreKids
        if ($savedSearch->pWantMoreKids[0] != ProfileSubstitutions::ANY &&
            !in_array($this->me->wantMoreKids, $savedSearch->pWantMoreKids)
        ) {
            return false;
        }

        // pEmploymentStatus
        if ($savedSearch->pEmploymentStatus[0] != ProfileSubstitutions::ANY &&
            !in_array($this->me->employmentStatus, $savedSearch->pEmploymentStatus)
        ) {
            return false;
        }

        // pField
        if ($savedSearch->pField[0] != ProfileSubstitutions::ANY &&
            !in_array($this->me->field, $savedSearch->pField)
        ) {
            return false;
        }

        // pAnnualIncome
        if ($savedSearch->pAnnualIncome[0] != ProfileSubstitutions::ANY &&
            !in_array($this->me->annualIncome, $savedSearch->pAnnualIncome)
        ) {
            return false;
        }

        // pResidence
        if ($savedSearch->pResidence[0] != ProfileSubstitutions::ANY &&
            !in_array($this->me->residence, $savedSearch->pResidence)
        ) {
            return false;
        }

        // pReligion
        if ($savedSearch->pReligion[0] != ProfileSubstitutions::ANY &&
            !in_array($this->me->religion, $savedSearch->pReligion)
        ) {
            return false;
        }

        // pWilling2Relocate
        if ($savedSearch->pWilling2Relocate[0] != ProfileSubstitutions::ANY &&
            !in_array($this->me->willing2Relocate, $savedSearch->pWilling2Relocate)
        ) {
            return false;
        }

        return true;
    }
}