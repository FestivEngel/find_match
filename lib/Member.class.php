<?php
/**
 * Yet Another Dating Site based on socionics
 * PHP, Linux, Apache, Nginx, Twig, MongoDB, Redis, RabbitMQ, AJAX, JQuery, JSON
 *
 * @author Igor Tikhonov <itikhonov83@gmail.com>
 */

class Member
{
    /**
     * @param MongoId|string $id
     * @return string
     */
    public static function getNicknameById($id)
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->members;
        $data = $collection->findOne(
            array(
                '_id' => $id instanceof MongoId ? $id : new MongoId($id),
            ),
            array('nickname' => true)
        );

        return $data['nickname'];
    }

    public static function getSociotypeById($id)
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->members;
        $data = $collection->findOne(
            array(
                '_id' => $id instanceof MongoId ? $id : new MongoId($id),
            ),
            array('sociotype' => true)
        );

        return $data['sociotype'];
    }

    public static function getLangById($id)
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->members;
        $data = $collection->findOne(
            array(
                '_id' => $id instanceof MongoId ? $id : new MongoId($id),
            ),
            array('lang' => true)
        );

        return $data['lang'];
    }

    /**
     * @param int $age
     * @return MongoDate
     */
    public static function age2BYearEnd($age)
    {
        $mongoDate = Member::age2BYear($age);

        return $mongoDate;
    }

    public static function age2BYear($age)
    {
        $date = new DateTime();
        $date->sub(new DateInterval('P' . $age . 'Y'));

        return new MongoDate($date->getTimestamp());
    }

    /**
     * @param int $age
     * @return MongoDate
     */
    public static function age2BYearStart($age)
    {
        $mongoDate = Member::age2BYear($age + 1);

        return $mongoDate;
    }

    /**
     * @param MongoId|string $id
     */
    public static function unsubscribe($id)
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->members;
        $newdata = array(
            '$set' => array(
                'mailing' => false,
            ),
        );
        $collection->update(
            array('_id' => $id instanceof MongoId ? $id : new MongoId($id)),
            $newdata);
    }

    /**
     * @param MongoId|string $id
     * @return bool
     */
    public static function getMailingById($id)
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->members;
        $data = $collection->findOne(
            array(
                '_id' => $id instanceof MongoId ? $id : new MongoId($id),
            ),
            array('mailing' => true)
        );

        return $data['mailing'];
    }

    /**
     * @param MongoId|string $id
     * @return bool
     */
    public static function getPaidById($id)
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->members;
        $data = $collection->findOne(
            array(
                '_id' => $id instanceof MongoId ? $id : new MongoId($id),
            ),
            array('paid' => true)
        );

        return $data['paid'];
    }

    public static function setPaid($id)
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->members;
        $newdata = array(
            '$set' => array(
                'paid' => true,
                'paidTs' => new MongoDate(),
            ),
        );
        $collection->update(
            array('_id' => $id instanceof MongoId ? $id : new MongoId($id)),
            $newdata);
    }

    /**
     * @param string $email
     * @return MongoId
     */
    public static function getIdByEmail($email)
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->members;
        $data = $collection->findOne(
            array(
                'authMethod' => Auth::METHOD_REGFORM,
                'email' => $email,
            ),
            array('_id' => true)
        );

        return $data['_id'];
    }

    /**
     * @param MongoId|string $id
     * @return string
     */
    public static function getEmailById($id)
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->members;
        $data = $collection->findOne(
            array(
                '_id' => $id instanceof MongoId ? $id : new MongoId($id),
            ),
            array('email' => true)
        );

        return $data['email'];
    }

    public static function getHasEmailById($id)
    {
        {
            $client = new MongoClient();
            $db = $client->db;
            $collection = $db->members;
            $data = $collection->findOne(
                array(
                    '_id' => $id instanceof MongoId ? $id : new MongoId($id),
                ),
                array('hasEmail' => true)
            );

            return $data['hasEmail'];
        }
    }

    /**
     * @param MongoId|string $id
     * @param string $password
     */
    public static function updatePassword($id, $password)
    {
        $password = Auth::cryptPassword($password);
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->members;
        $newdata = array(
            '$set' => array(
                'password' => $password,
            ),
        );
        $collection->update(
            array('_id' => $id instanceof MongoId ? $id : new MongoId($id)),
            $newdata);
    }

    /**
     * @param MongoId|string $id
     * @return bool
     */
    public static function need2SendLikeAlert($id)
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->members;
        $data = $collection->findOne(
            array(
                '_id' => $id instanceof MongoId ? $id : new MongoId($id),
            ),
            array(
                'mailing' => true,
                'paid' => true,
                'likesAlerts' => true
            ));

        return ($data['mailing'] && $data['paid'] && $data['likesAlerts']);
    }

    /**
     * @param MongoId|string $id
     * @return bool
     */
    public static function need2SendViewAlert($id)
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->members;
        $data = $collection->findOne(
            array(
                '_id' => $id instanceof MongoId ? $id : new MongoId($id),
            ),
            array(
                'mailing' => true,
                'paid' => true,
                'viewsAlerts' => true
            ));

        return ($data['mailing'] && $data['paid'] && $data['viewsAlerts']);
    }

    /**
     * @param MongoId|string $id
     * @param string $fileName
     */
    public static function updatePhoto($id, $fileName)
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->members;
        $newdata = array(
            '$set' => array(
                'hasPhoto' => true,
                'photoFileName' => $fileName,
            ),
        );
        $collection->update(
            array('_id' => $id instanceof MongoId ? $id : new MongoId($id)),
            $newdata);
    }

    /**
     * @param MongoId|string $id
     * @param string $email
     */
    public static function updateEmail($id, $email)
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->members;
        $newdata = array(
            '$set' => array(
                'hasEmail' => true,
                'email' => $email,
            ),
        );
        $collection->update(
            array('_id' => $id instanceof MongoId ? $id : new MongoId($id)),
            $newdata);
    }

    /**
     * @param MongoId|string $id
     * @return string
     */
    public static function getBase64EncodedImage($id)
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->members;
        $data = $collection->findOne(
            array(
                '_id' => $id instanceof MongoId ? $id : new MongoId($id),
            ),
            array(
                'hasPhoto' => true,
                'photoFileName' => true,
            )
        );
        if ($data['hasPhoto']) {
            $imagePath = Configuration::getThumbFullFileName($data['photoFileName']);
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $type = finfo_file($finfo, $imagePath);
            finfo_close($finfo);
            $encodedImage = '<img src="data:' . $type . ';base64,' . base64_encode(file_get_contents($imagePath)) . '">';

            return $encodedImage;
        }

        return '';
    }

    /**
     * @var array
     */
    private
        $keys = array(
        '_id',
        'authMethod',
        'active',
        'mailing',
        'paid',
        'paidTs',
        'matchingOn',
        'newMatchesAlerts',
        'likesAlerts',
        'viewsAlerts',
        'profileFilled',
        'regTs',
        'lastLoginTs',
        'userName',
        'nickname',
        'invitedBy',
        'email',
        'hasEmail',
        'password',
        'testTaken',
        'sociotype',
        'firstName',
        'lastName',
        'hasPhoto',
        'photoFileName',
        'bDate',
        'gender',
        'country',
        'city',
        'lang',
        'newMsgsAlerts',
        'iam',
        'lookingFor',
        'livingCity',
        'livingState',
        'livingCountry',
        'height',
        'hairColor',
        'hairLength',
        'hairType',
        'eyeColor',
        'eyeWear',
        'weight',
        'BMI',
        'bodyType',
        'ethnicity',
        'bodyArt',
        'drinking',
        'smoking',
        'maritalStatus',
        'kids',
        'numberOfKids',
        'wantMoreKids',
        'employmentStatus',
        'field',
        'annualIncome',
        'residence',
        'religion',
        'education',
        'willing2Relocate',
    );

    /**
     * @var array
     */
    private $data = [];

    /**
     * @var bool
     */
    public $inFavorites = false;

    /**
     * @var bool
     */
    public $viewed = false;

    /**
     * @var string
     */
    public $relations = '';

    /**
     * @var bool
     */
    public $exclude = false;

    /**
     * @var bool
     */
    public $mutualLike = false;

    public function __construct()
    {
        foreach ($this->keys as $key) {
            $value = ProfileSubstitutions::ANY;
            if ($key == 'active') {
                $value = true;
            } else if ($key == 'paid') {
                $value = false;
            } else if ($key == 'paidTs') {
                $value = null;
            } else if ($key == 'mailing') {
                $value = true;
            } else if ($key == 'matchingOn') {
                $value = true;
            } else if ($key == 'newMatchesAlerts') {
                $value = false;
            } else if ($key == 'likesAlerts' || $key == 'viewsAlerts') {
                $value = false;
            } else if ($key == 'profileFilled') {
                $value = false;
            } else if ($key == 'regTs' || $key == 'lastLoginTs') {
                $value = new MongoDate();
            } else if ($key == 'userName') {
                $value = null;
            } else if ($key == 'hasEmail') {
                $value = true;
            } else if ($key == 'testTaken') {
                $value = false;
            } else if ($key == 'firstName' || $key == 'lastName') {
                $value = null;
            } else if ($key == 'hasPhoto') {
                $value = false;
            } else if ($key == 'photoFileName') {
                $value = null;
            } else if ($key == 'education') {
                $value = ProfileSubstitutions::ANY;
            } else if ($key == 'country' || $key == 'city') {
                $value = null;
            } else if ($key == 'iam' || $key == 'lookingFor') {
                $value = null;
            } else if ($key == 'livingCity' || $key == 'livingCountry') {
                $value = ProfileSubstitutions::ANY;
            } else if ($key == 'lang') {
                $value = 'en';
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

        if ($name == 'bDate') {
            $this->data['bDate'] = $value instanceof MongoDate ? $value : new MongoDate($value);

            return;
        }

        if ($name == "password") {
            $value = Auth::cryptPassword($value);
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
        if ($name == 'data') {
            return $this->data;
        }

        if ($name == 'id') {
            return $this->data['_id'];
        }

        if ($name == 'bDay') {
            return date('j', $this->data['bDate']->sec);
        }

        if ($name == 'bMonth') {
            return date('n', $this->data['bDate']->sec);
        }

        if ($name == 'bYear') {
            return date('Y', $this->data['bDate']->sec);
        }

        if ($name == 'age') {
            $date = date('Y', $this->data['bDate']->sec) . '-' . date('m', $this->data['bDate']->sec) . '-' . date('d', $this->data['bDate']->sec);
            return date_diff(date_create($date), date_create('today'))->y;
        }

        if ($name == 'code') {
            return RelationsMatrix::getSocionicsNumCode($this->data['sociotype']);
        }

        if ($name == 'countryName') {
            return Living::$countries[$this->data['livingCountry']];
        }

        if ($name == 'cityName') {
            switch ($this->data['livingCountry']) {
                case 1:
                    return Living::$citiesUnitedStates[$this->data['livingCity']];

                case 2:
                    return Living::$citiesCanada[$this->data['livingCity']];

                case 3:
                    return Living::$citiesRussia[$this->data['livingCity']];

                case 4:
                    return Living::$citiesUkraine[$this->data['livingCity']];

                case 5:
                    return Living::$citiesKazakhstan[$this->data['livingCity']];

                case 6:
                    return Living::$citiesBelarus[$this->data['livingCity']];
            }
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

        if ($name == 'bDay' || $name == 'bMonth' || $name == 'bYear' || $name == 'age') {
            return true;
        }

        if ($name == 'code') {
            return true;
        }

        if ($name == 'countryName' || $name == 'cityName') {
            return true;
        }

        if (in_array($name, $this->keys)) {
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
        $collection = $db->members;
        if (isset($this->data['_id'])) {
            $this->data = $collection->findOne(
                array(
                    '_id' => $this->data['_id'],
                ),
                $this->keys
            );
        } else if (isset($this->data['email'])) {
            $this->data = $collection->findOne(
                array(
                    'email' => $this->data['email'],
                ),
                $this->keys
            );
        }

        if ($this->data) {
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    public function store()
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->members;
        $collection->insert($this->data);
        $result = $collection->findOne(
            array(
                'email' => $this->email,
            ),
            array(
                '_id' => true,
            ));
        $this->id = $result['_id'];

        return $result['_id'];
    }

    public function updateLastLoginTs()
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->members;
        $newdata = array(
            '$set' => array(
                'lastLoginTs' => new MongoDate(),
            ),
        );
        $collection->update(
            array('_id' => $this->data['_id']),
            $newdata);
    }

    /**
     * @return bool
     */
    public function isInDb()
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->members;
        $record = NULL;
        if ($this->data['authMethod'] == Auth::METHOD_REGFORM ||
            $this->data['authMethod'] == Auth::METHOD_GOOGLE ||
            $this->data['authMethod'] == Auth::METHOD_FB
        ) {
            $record = $collection->findOne(
                array(
                    'email' => $this->data['email'],
                ),
                array(
                    '_id' => true,
                ));
        }

        if ($record === NULL) {
            return false;
        }

        $this->data['_id'] = $record['_id'];

        return true;
    }

    /**
     * @return bool
     */
    public function isInDbVK()
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->members;
        $record = $collection->findOne(
            array(
                'userName' => $this->data['userName'],
            ),
            array(
                '_id' => true,
            ));

        if ($record === NULL) {
            return false;
        }

        $this->data['_id'] = $record['_id'];

        return true;
    }

    /**
     * @return int
     */
    public function isTestTaken()
    {
        return $this->data['testTaken'];
    }

    public function updateSociotype()
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->members;
        $newdata = array(
            '$set' => array(
                'sociotype' => $this->data['sociotype'],
                'testTaken' => true,
            ),
        );
        $collection->update(
            array('_id' => $this->data['_id']),
            $newdata);
    }

    public function updateLang()
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->members;
        $newdata = array(
            '$set' => array(
                'lang' => $this->data['lang'],
            ),
        );
        $collection->update(
            array('_id' => $this->data['_id']),
            $newdata);
    }

    /**
     * @param bool $state
     */
    public function setNewMsgsAlerts($state)
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->members;
        $newdata = array(
            '$set' => array(
                'newMsgsAlerts' => $state,
            ),
        );
        $collection->update(
            array('_id' => $this->data['_id']),
            $newdata);
    }

    /**
     * @param bool $state
     */
    public function setActive($state)
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->members;
        $newdata = array(
            '$set' => array(
                'active' => $state,
            ),
        );
        $collection->update(
            array('_id' => $this->data['_id']),
            $newdata);
    }

    /**
     * @param bool $state
     */
    public function setMatching($state)
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->members;
        $newdata = array(
            '$set' => array(
                'matchingOn' => $state,
            ),
        );
        $collection->update(
            array('_id' => $this->data['_id']),
            $newdata);
    }

    /**
     * @param bool $state
     */
    public function setLikesAlerts($state)
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->members;
        $newdata = array(
            '$set' => array(
                'likesAlerts' => $state,
            ),
        );
        $collection->update(
            array('_id' => $this->data['_id']),
            $newdata);
    }

    /**
     * @param bool $state
     */
    public function setViewsAlerts($state)
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->members;
        $newdata = array(
            '$set' => array(
                'viewsAlerts' => $state,
            ),
        );
        $collection->update(
            array('_id' => $this->data['_id']),
            $newdata);
    }

    public function updateProfile()
    {
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->members;
        $newBDate = mktime(0, 0, 0, $_REQUEST['bMonth'], $_REQUEST['bDay'], $_REQUEST['bYear']);
        $newdata = array(
            '$set' => array(
                'mailing' => isset($_REQUEST['mailing']),
                'newMatchesAlerts' => isset($_REQUEST['newMatchesAlerts']),
                'bDate' => new MongoDate($newBDate),
                'gender' => (int)$_REQUEST['gender'],
                'iam' => $_REQUEST['iam'],
                'lookingFor' => $_REQUEST['lookingFor'],
                'livingCity' => (int)$_REQUEST['livingCity'],
                'livingState' => (int)$_REQUEST['livingState'],
                'livingCountry' => (int)$_REQUEST['livingCountry'],
                'height' => (int)$_REQUEST['height'],
                'hairColor' => (int)$_REQUEST['hairColor'],
                'hairLength' => (int)$_REQUEST['hairLength'],
                'hairType' => (int)$_REQUEST['hairType'],
                'eyeColor' => (int)$_REQUEST['eyeColor'],
                'eyeWear' => (int)$_REQUEST['eyeWear'],
                'weight' => (int)$_REQUEST['weight'],
                'BMI' => (int)$_REQUEST['BMI'],
                'bodyType' => (int)$_REQUEST['bodyType'],
                'ethnicity' => (int)$_REQUEST['ethnicity'],
                'bodyArt' => (int)$_REQUEST['bodyArt'],
                'drinking' => (int)$_REQUEST['drinking'],
                'smoking' => (int)$_REQUEST['smoking'],
                'maritalStatus' => (int)$_REQUEST['maritalStatus'],
                'kids' => (int)$_REQUEST['kids'],
                'numberOfKids' => (int)$_REQUEST['numberOfKids'],
                'wantMoreKids' => (int)$_REQUEST['wantMoreKids'],
                'employmentStatus' => (int)$_REQUEST['employmentStatus'],
                'field' => (int)$_REQUEST['field'],
                'annualIncome' => (int)$_REQUEST['annualIncome'],
                'residence' => (int)$_REQUEST['residence'],
                'religion' => (int)$_REQUEST['religion'],
                'education' => (int)$_REQUEST['education'],
                'willing2Relocate' => (int)$_REQUEST['willing2Relocate'],
                'sociotype' => (int)$_REQUEST['sociotype'],

                'profileFilled' => true,
            ),
        );
        $collection->update(
            array('_id' => $this->data['_id']),
            $newdata);
    }

    public function delete()
    {
        ContactList::deleteContacts($this->data['_id']);
        SavedSearch::deleteSearches($this->data['_id']);
        ProfileView::deleteViews($this->data['_id']);
        Like::deleteLikes($this->data['_id']);
        $mailer = new Mailer();
        $mailer->data['type'] = Mailer::TYPE_BYE;
        $mailer->data['lang'] = $this->data['lang'];
        $mailer->data['email'] = $this->data['email'];
        $mailer->data['userName'] = $this->data['nickname'];
        $mailer->putInQueue();
        $client = new MongoClient();
        $db = $client->db;
        $collection = $db->members;
        $collection->remove(array('_id' => $this->data['_id']));
    }

    /**
     * @return string
     */
    public function createDescription()
    {
        $description = $this->data['nickname'] . ', ';
        $description .= $this->data['email'] . ', ';
        $description .= $this->data['sociotype'] . ', ';
        $description .= $this->data['hasPhoto'] ? 'photo, ' : 'no photo, ';
        $description .= $this->age . ', ';
        $description .= $this->data['gender'] . ', ';
        $description .= $this->data['livingCity'] . ', ';
        $description .= $this->data['livingState'] . ', ';
        $description .= $this->data['livingCountry'] . ', ';
        $description .= $this->data['height'] . ', ';
        $description .= $this->data['hairColor'] . ', ';
        $description .= $this->data['hairLength'] . ', ';
        $description .= $this->data['hairType'] . ', ';
        $description .= $this->data['eyeColor'] . ', ';
        $description .= $this->data['eyeWear'] . ', ';
        $description .= $this->data['weight'] . ', ';
        $description .= $this->data['BMI'] . ', ';
        $description .= $this->data['bodyType'] . ', ';
        $description .= $this->data['ethnicity'] . ', ';
        $description .= $this->data['bodyArt'] . ', ';
        $description .= $this->data['drinking'] . ', ';
        $description .= $this->data['smoking'] . ', ';
        $description .= $this->data['maritalStatus'] . ', ';
        $description .= $this->data['kids'] . ', ';
        $description .= $this->data['numberOfKids'] . ', ';
        $description .= $this->data['wantMoreKids'] . ', ';
        $description .= $this->data['employmentStatus'] . ', ';
        $description .= $this->data['field'] . ', ';
        $description .= $this->data['annualIncome'] . ', ';
        $description .= $this->data['residence'] . ', ';
        $description .= $this->data['religion'] . ', ';
        $description .= $this->data['education'] . ', ';
        $description .= $this->data['willing2Relocate'];

        return $description;
    }

    public function createLookingForDescription()
    {
        $description = 'Hello, I am ';
        $description .= $this->data['nickname'];
        $description .= '. I\'m from ' . $this->cityName . ', ' . $this->countryName;
        $description .= '. And I look for somebody.';

        return $description;
    }
}