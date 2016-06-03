<?php
/**
 * Yet Another Dating Site based on socionics
 * PHP, Linux, Apache, Nginx, Twig, MongoDB, Redis, RabbitMQ, AJAX, JQuery, JSON
 *
 * @author Igor Tikhonov <itikhonov83@gmail.com>
 */

class SavedSearch
{
    const SORTBY_LastActivityDate = 1;
    const SORTBY_WithPhotoFirst = 2;
    const SORTBY_Age = 3;
    const SORTBY_Сompatibility = 4;
    const SORTBY_UnviewedFirst = 5;

    const ALERTS_NEVER = 1;
    const ALERTS_DAILY = 2;
    const ALERTS_WEEKLY = 3;

    const RELATIONS_TYPE_DUAL = 1;

    public static $alertStr = array(
        SavedSearch::ALERTS_NEVER => 'SavedSearch::ALERTS_NEVER',
        SavedSearch::ALERTS_DAILY => 'SavedSearch::ALERTS_DAILY',
        SavedSearch::ALERTS_WEEKLY => 'SavedSearch::ALERTS_WEEKLY',
    );

    /**
     * @param MongoId|string $searchId
     * @param string $name
     */
    public static function setSaved($searchId, $name)
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->searches;
        $newdata = array(
            '$set' => array(
                'saved' => true,
                'name' => $name,
            ),
        );
        $collection->update(
            array('_id' => $searchId instanceof MongoId ? $searchId : new MongoId($searchId)),
            $newdata);
    }

    /**
     * @param MongoId|string $userId
     */
    public static function deleteSearches($userId)
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->searches;
        $collection->remove(array('userId' => $userId instanceof MongoId ? $userId : new MongoId($userId)));
    }

    /**
     * @var array
     */
    private $keys = array(
        '_id',
        'userId',
        'isDefault',
        'ts',
        'lastRunTs',
        'name',
        'saved',
        'onlyDualSociotypes',
        'onlyReverseMatch',
        'excludeViewed',
        'excludeFavorites',
        'onlyProfilesWithPhoto',
        'sortBy',
        'alerts',

        // Preferences
        // Scalar
        'pGender',
        'pAgeFrom',
        'pAgeTo',
        'pLivingCity',
        'pLivingState',
        'pLivingCountry',
        'pRelationsType',
        'pSociotype',
        'pHeightFrom',
        'pHeightTo',
        'pWeightFrom',
        'pWeightTo',
        'pBMIFrom',
        'pBMITo',
        'pNumberOfKids',
        'pEducation',
        // Arrays
        'pHairColor',
        'pHairLength',
        'pHairType',
        'pEyeColor',
        'pEyeWear',
        'pBodyType',
        'pEthnicity',
        'pBodyArt',
        'pDrinking',
        'pSmoking',
        'pMaritalStatus',
        'pKids',
        'pWantMoreKids',
        'pEmploymentStatus',
        'pField',
        'pAnnualIncome',
        'pResidence',
        'pReligion',
        'pWilling2Relocate',
    );

    /**
     * @var array
     */
    private $data = [];

    public function __construct()
    {
        foreach ($this->keys as $key) {
            $value = ProfileSubstitutions::ANY;
            if ($key == 'isDefault') {
                $value = false;
            } else if ($key == 'ts' || $key == 'lastRunTs') {
                $value = new MongoDate();
            } else if ($key == 'name') {
                $value = '';
            } else if ($key == 'saved') {
                $value = false;
            } else if ($key == 'onlyDualSociotypes') {
                $value = true;
            } else if ($key == 'onlyReverseMatch' ||
                $key == 'excludeViewed' ||
                $key == 'excludeFavorites'
            ) {
                $value = false;
            } else if ($key == 'onlyProfilesWithPhoto') {
                $value = true;
            } else if ($key == 'sortBy') {
                $value = SavedSearch::SORTBY_LastActivityDate;
            } else if ($key == 'alerts') {
                $value = SavedSearch::ALERTS_NEVER;
            } else if ($key == 'pEducation') {
                $value = ProfileSubstitutions::ANY;
            } else if ($key == 'pGender' || $key == 'pAgeFrom' || $key == 'pAgeTo' || $key == 'pLivingState' ||
                $key == 'pSociotype' || $key == 'pHeightFrom' || $key == 'pHeightTo' ||
                $key == 'pWeightFrom' || $key == 'pWeightTo' || $key == 'pBMIFrom' ||
                $key == 'pBMITo' || $key == 'pNumberOfKids'
            ) {
                $value = ProfileSubstitutions::ANY;
            } else if ($key == 'pHairColor' || $key == 'pHairLength' || $key == 'pHairType' ||
                $key == 'pEyeColor' || $key == 'pEyeWear' || $key == 'pBodyType' ||
                $key == 'pEthnicity' || $key == 'pBodyArt' || $key == 'pDrinking' || $key == 'pSmoking' ||
                $key == 'pMaritalStatus' || $key == 'pKids' || $key == 'pWantMoreKids' ||
                $key == 'pEmploymentStatus' || $key == 'pField' || $key == 'pAnnualIncome' ||
                $key == 'pResidence' || $key == 'pReligion' || $key == 'pWilling2Relocate'
            ) {
                $value = array(ProfileSubstitutions::ANY);
            } else if ($key == 'pLivingCity' || $key == 'pLivingCountry') {
                $value = 0;
            }

            unset($this->data['_id']);
            $this->data[$key] = $value;
        }
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        if ($name == 'id') {
            $this->data['_id'] = $value instanceof MongoId ? $value : new MongoId($value);

            return;
        }

        if ($name == 'userId') {
            $this->data['userId'] = $value instanceof MongoId ? $value : new MongoId($value);
        }

        if ($name == 'data') {
            $this->data = $value;
        }

        if (in_array($name, $this->keys)) {
            $this->data[$name] = $value;
        }
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public function __get($name)
    {
        if ($name == 'id') {
            return $this->data['_id'];
        }

        if ($name == 'data') {
            return $this->data;
        }

        if (in_array($name, $this->keys)) {
            return $this->data[$name];
        }

        return null;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function __isset($name)
    {
        if ($name == 'id') {
            return true;
        }

        if (in_array($name, $this->keys)) {
            return true;
        }

        return false;
    }

    public function createDefault()
    {
        $this->data['isDefault'] = true;
        $this->data['name'] = 'Default';
        $this->data['saved'] = true;
        $this->data['onlyDualSociotypes'] = true;
        $this->data['onlyReverseMatch'] = false;
        $this->data['excludeViewed'] = false;
        $this->data['excludeFavorites'] = false;
        $this->data['onlyProfilesWithPhoto'] = true;
        $this->data['sortBy'] = SavedSearch::SORTBY_LastActivityDate;
        $this->data['alerts'] = SavedSearch::ALERTS_NEVER;
        $this->data['pRelationsType'] = SavedSearch::RELATIONS_TYPE_DUAL;
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->searches;
        $collection->insert($this->data);
    }

    /**
     * @return bool
     */
    public function loadDefault()
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->searches;
        $this->data = $collection->findOne(array(
            'userId' => $this->data['userId'],
            'isDefault' => true),
            $this->keys);
        if ($this->data) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function load()
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->searches;
        $this->data = $collection->findOne(array(
            '_id' => $this->data['_id']),
            $this->keys);

        if ($this->data) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isForCurrentUser()
    {
        return (string)$this->data['userId'] == (string)$_SESSION['userId'];
    }

    public function setAll()
    {
        /**
         * @param $array
         * @return array
         */
        $conv = function ($array) {
            $newArray = [];
            foreach ($array as $key => $value) {
                $newArray[$key] = (int)$value;
            }

            return $newArray;
        };

        if ($_REQUEST['id'] != '') {
            $this->id = $_REQUEST['id'];
        }

        $this->userId = $_SESSION['userId'];
        $this->name = $_REQUEST['name'];
        $this->onlyDualSociotypes = isset($_REQUEST['onlyDualSociotypes']);
        $this->onlyReverseMatch = isset($_REQUEST['onlyReverseMatch']);
        $this->excludeViewed = isset($_REQUEST['excludeViewed']);
        $this->excludeFavorites = isset($_REQUEST['excludeFavorites']);
        $this->onlyProfilesWithPhoto = isset($_REQUEST['onlyProfilesWithPhoto']);
        $this->alerts = (int)$_REQUEST['alerts'];
        // Preferences
        $this->sortBy = (int)$_REQUEST['sortBy'];
        $this->pGender = (int)$_REQUEST['pGender'];
        $this->pAgeFrom = (int)$_REQUEST['pAgeFrom'];
        $this->pAgeTo = (int)$_REQUEST['pAgeTo'];
        $this->pLivingCity = (int)$_REQUEST['pLivingCity'];
        $this->pLivingState = (int)$_REQUEST['pLivingState'];
        $this->pLivingCountry = (int)$_REQUEST['pLivingCountry'];
        $this->pSociotype = (int)$_REQUEST['pSociotype'];
        $this->pHeightFrom = (int)$_REQUEST['pHeightFrom'];
        $this->pHeightTo = (int)$_REQUEST['pHeightTo'];
        $this->pWeightFrom = (int)$_REQUEST['pWeightFrom'];
        $this->pWeightTo = (int)$_REQUEST['pWeightTo'];
        $this->pBMIFrom = (int)$_REQUEST['pBMIFrom'];
        $this->pBMITo = (int)$_REQUEST['pBMITo'];
        $this->pNumberOfKids = (int)$_REQUEST['pNumberOfKids'];
        $this->pEducation = (int)$_REQUEST['pEducation'];

        if (!$_REQUEST['pHairColor']) {
            $this->pHairColor = array(ProfileSubstitutions::ANY);
        } else {
            $this->pHairColor = $conv($_REQUEST['pHairColor']);
        }

        if (!$_REQUEST['pHairLength']) {
            $this->pHairLength = array(ProfileSubstitutions::ANY);
        } else {
            $this->pHairLength = $conv($_REQUEST['pHairLength']);
        }

        if (!$_REQUEST['pHairType']) {
            $this->pHairType = array(ProfileSubstitutions::ANY);
        } else {
            $this->pHairType = $conv($_REQUEST['pHairType']);
        }

        if (!$_REQUEST['pEyeColor']) {
            $this->pEyeColor = array(ProfileSubstitutions::ANY);
        } else {
            $this->pEyeColor = $conv($_REQUEST['pEyeColor']);
        }

        if (!$_REQUEST['pEyeWear']) {
            $this->pEyeWear = array(ProfileSubstitutions::ANY);
        } else {
            $this->pEyeWear = $conv($_REQUEST['pEyeWear']);
        }

        if (!$_REQUEST['pBodyType']) {
            $this->pBodyType = array(ProfileSubstitutions::ANY);
        } else {
            $this->pBodyType = $conv($_REQUEST['pBodyType']);
        }

        if (!$_REQUEST['pEthnicity']) {
            $this->pEthnicity = array(ProfileSubstitutions::ANY);
        } else {
            $this->pEthnicity = $conv($_REQUEST['pEthnicity']);
        }

        if (!$_REQUEST['pBodyArt']) {
            $this->pBodyArt = array(ProfileSubstitutions::ANY);
        } else {
            $this->pBodyArt = $conv($_REQUEST['pBodyArt']);
        }

        if (!$_REQUEST['pDrinking']) {
            $this->pDrinking = array(ProfileSubstitutions::ANY);
        } else {
            $this->pDrinking = $conv($_REQUEST['pDrinking']);
        }

        if (!$_REQUEST['pSmoking']) {
            $this->pSmoking = array(ProfileSubstitutions::ANY);
        } else {
            $this->pSmoking = $conv($_REQUEST['pSmoking']);
        }

        if (!$_REQUEST['pMaritalStatus']) {
            $this->pMaritalStatus = array(ProfileSubstitutions::ANY);
        } else {
            $this->pMaritalStatus = $conv($_REQUEST['pMaritalStatus']);
        }

        if (!$_REQUEST['pKids']) {
            $this->pKids = array(ProfileSubstitutions::ANY);
        } else {
            $this->pKids = $conv($_REQUEST['pKids']);
        }

        if (!$_REQUEST['pWantMoreKids']) {
            $this->pWantMoreKids = array(ProfileSubstitutions::ANY);
        } else {
            $this->pWantMoreKids = $conv($_REQUEST['pWantMoreKids']);
        }

        if (!$_REQUEST['pEmploymentStatus']) {
            $this->pEmploymentStatus = array(ProfileSubstitutions::ANY);
        } else {
            $this->pEmploymentStatus = $conv($_REQUEST['pEmploymentStatus']);
        }

        if (!$_REQUEST['pField']) {
            $this->pField = array(ProfileSubstitutions::ANY);
        } else {
            $this->pField = $conv($_REQUEST['pField']);
        }

        if (!$_REQUEST['pAnnualIncome']) {
            $this->pAnnualIncome = array(ProfileSubstitutions::ANY);
        } else {
            $this->pAnnualIncome = $conv($_REQUEST['pAnnualIncome']);
        }

        if (!$_REQUEST['pResidence']) {
            $this->pResidence = array(ProfileSubstitutions::ANY);
        } else {
            $this->pResidence = $conv($_REQUEST['pResidence']);
        }

        if (!$_REQUEST['pReligion']) {
            $this->pReligion = array(ProfileSubstitutions::ANY);
        } else {
            $this->pReligion = $conv($_REQUEST['pReligion']);
        }

        if (!$_REQUEST['pWilling2Relocate']) {
            $this->pWilling2Relocate = array(ProfileSubstitutions::ANY);
        } else {
            $this->pWilling2Relocate = $conv($_REQUEST['pWilling2Relocate']);
        }
    }

    public function updateDefault()
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->searches;
        $userId = $this->data['userId'];
        unset($this->data['_id']);
        unset($this->data['userId']);
        unset($this->data['isDefault']);
        unset($this->data['name']);
        unset($this->data['saved']);
        unset($this->data['onlyDualSociotypes']);
        unset($this->data['excludeViewed']);
        unset($this->data['excludeFavorites']);
        unset($this->data['onlyProfilesWithPhoto']);
        $newdata = array('$set' => $this->data);
        $collection->update(
            array(
                'userId' => $userId,
                'isDefault' => true),
            $newdata);
    }

    public function store()
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->searches;
        unset($this->data['_id']);
        $collection->insert($this->data);
    }

    public function update()
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->searches;
        $id = $this->data['_id'];
        unset($this->data['_id']);
        $userId = $this->data['userId'];
        unset($this->data['userId']);
        unset($this->data['isDefault']);
        unset($this->data['saved']);
        $newdata = array('$set' => $this->data);
        $collection->update(
            array(
                '_id' => $id,
                'userId' => $userId),
            $newdata);
    }

    public function updateLastRunTs()
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->searches;
        $newdata = array(
            '$set' => array(
                'lastRunTs' => new MongoDate(),
            ),
        );
        $collection->update(
            array('_id' => $this->data['_id']),
            $newdata);
    }

    public function delete()
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->searches;
        $collection->remove(array(
            '_id' => $this->data['_id'],
            'userId' => $this->data['userId'],
        ));
    }

    /**
     * @param string $value
     * @return bool
     */
    public function isPHairColorChecked($value)
    {
        return in_array($value, $this->data['pHairColor']);
    }

    /**
     * @param string $value
     * @return bool
     */
    public function isPHairLengthChecked($value)
    {
        return in_array($value, $this->data['pHairLength']);
    }

    /**
     * @param string $value
     * @return bool
     */
    public function isPHairTypeChecked($value)
    {
        return in_array($value, $this->data['pHairType']);
    }

    /**
     * @param string $value
     * @return bool
     */
    public function isPEyeColorChecked($value)
    {
        return in_array($value, $this->data['pEyeColor']);
    }

    /**
     * @param string $value
     * @return bool
     */
    public function isPEyeWearChecked($value)
    {
        return in_array($value, $this->data['pEyeWear']);
    }

    /**
     * @param string $value
     * @return bool
     */
    public function isPBodyTypeChecked($value)
    {
        return in_array($value, $this->data['pBodyType']);
    }

    /**
     * @param string $value
     * @return bool
     */
    public function isPEthnicityChecked($value)
    {
        return in_array($value, $this->data['pEthnicity']);
    }

    /**
     * @param string $value
     * @return bool
     */
    public function isPBodyArtChecked($value)
    {
        return in_array($value, $this->data['pBodyArt']);
    }

    /**
     * @param string $value
     * @return bool
     */
    public function isPDrinkingChecked($value)
    {
        return in_array($value, $this->data['pDrinking']);
    }

    /**
     * @param string $value
     * @return bool
     */
    public function isPSmokingChecked($value)
    {
        return in_array($value, $this->data['pSmoking']);
    }

    /**
     * @param string $value
     * @return bool
     */
    public function isPMaritalStatusChecked($value)
    {
        return in_array($value, $this->data['pMaritalStatus']);
    }

    /**
     * @param string $value
     * @return bool
     */
    public function isPKidsChecked($value)
    {
        return in_array($value, $this->data['pKids']);
    }

    /**
     * @param string $value
     * @return bool
     */
    public function isPWantMoreKidsChecked($value)
    {
        return in_array($value, $this->data['pWantMoreKids']);
    }

    /**
     * @param string $value
     * @return bool
     */
    public function isPEmploymentStatusChecked($value)
    {
        return in_array($value, $this->data['pEmploymentStatus']);
    }

    /**
     * @param string $value
     * @return bool
     */
    public function isPFieldChecked($value)
    {
        return in_array($value, $this->data['pField']);
    }

    /**
     * @param string $value
     * @return bool
     */
    public function isPAnnualIncomeChecked($value)
    {
        return in_array($value, $this->data['pAnnualIncome']);
    }

    /**
     * @param string $value
     * @return bool
     */
    public function isPResidenceChecked($value)
    {
        return in_array($value, $this->data['pResidence']);
    }

    /**
     * @param string $value
     * @return bool
     */
    public function isPReligionChecked($value)
    {
        return in_array($value, $this->data['pReligion']);
    }

    /**
     * @param string $value
     * @return bool
     */
    public function isPWilling2RelocateChecked($value)
    {
        return in_array($value, $this->data['pWilling2Relocate']);
    }
}