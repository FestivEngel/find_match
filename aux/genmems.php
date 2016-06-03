<?php
/**
 * Yet Another Dating Site based on socionics
 * PHP, Linux, Apache, Nginx, Twig, MongoDB, Redis, RabbitMQ, AJAX, JQuery, JSON
 *
 * @author Igor Tikhonov <itikhonov83@gmail.com>
 */

require_once dirname(__FILE__) . '/../lib/autoload.php';
require_once dirname(__FILE__) . '/../vendor/autoload.php';

for ($i = 0; $i < 10000; $i++) {
    $year = rand(1946, 1998);
    $month = rand(1, 12);
    $day = rand(1, 28);
    $gender = rand(1, 2);
    $code = rand(1, 16);
    $height = rand(160, 220);
    $hairColor = rand(1, 8);
    $hairLength = rand(1, 5);
    $hairType = rand(1, 4);
    $eyeColor = rand(1, 6);
    $eyeWear = rand(1, 3);
    $weight = rand(40, 160);
    $BMI = rand(1, 7);
    $bodyType = rand(1, 7);
    $ethnicity = rand(1, 9);
    $bodyArt = rand(1, 7);
    $drinking = rand(1, 3);
    $smoking = rand(1, 3);
    $maritalStatus = rand(1, 5);
    $kids = rand(1, 4);
    $numberOfKids = rand(1, 10);
    $wantMoreKids = rand(1, 3);
    $employmentStatus = rand(1, 7);
    $field = rand(1, 32);
    $annualIncome = rand(1, 9);
    $residence = rand(1, 7);
    $religion = rand(1, 16);
    $education = rand(1, 7);
    $willing2Relocate = rand(1, 4);
    $livingCountry = rand(1, count(Living::$countries));
    $livingCity = rand(1, 9);
    $paid = rand(1, 2);

    $member = new Member();
    $member->authMethod = Auth::METHOD_REGFORM;
    $member->profileFilled = true;
    $member->gender = $gender;
    $member->nickname = $gender % 2 ? 'John' : 'Mary';
    $member->nickname .= $i;
    $dateTime = new DateTime();
    $dateTime->setTime(0, 0, 0);
    $dateTime->setDate($year, $month, $day);
    $member->bDate = $dateTime->getTimestamp();
    $member->email = $member->nickname . '@test.com';
    $member->password = 'Qwerty123';
    $member->testTaken = true;
    $member->sociotype = $code;
    $member->invitedBy = 0;
    $member->lang = 'en';
    $member->height = $height;
    $member->hairColor = $hairColor;
    $member->hairLength = $hairLength;
    $member->hairType = $hairType;
    $member->eyeColor = $eyeColor;
    $member->eyeWear = $eyeWear;
    $member->weight = $weight;
    $member->BMI = $BMI;
    $member->bodyType = $bodyType;
    $member->ethnicity = $ethnicity;
    $member->bodyArt = $bodyArt;
    $member->drinking = $drinking;
    $member->smoking = $smoking;
    $member->maritalStatus = $maritalStatus;
    $member->kids = $kids;
    $member->numberOfKids = $numberOfKids;
    $member->wantMoreKids = $wantMoreKids;
    $member->employmentStatus = $employmentStatus;
    $member->field = $field;
    $member->annualIncome = $annualIncome;
    $member->residence = $residence;
    $member->religion = $religion;
    $member->education = $education;
    $member->willing2Relocate = $willing2Relocate;
    $member->livingCountry = $livingCountry;
    $member->livingCity = $livingCity;
    $member->iam = $member->createDescription();
    $member->lookingFor = $member->createLookingForDescription();
    $member->paid = $paid % 2 == 0;
    $userId = $member->store();
    $savedSearch = new SavedSearch();
    $savedSearch->userId = $userId;
    $savedSearch->createDefault();
}