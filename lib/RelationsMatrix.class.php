<?php
/**
 * Yet Another Dating Site based on socionics
 * PHP, Linux, Apache, Nginx, Twig, MongoDB, Redis, RabbitMQ, AJAX, JQuery, JSON
 *
 * @author Igor Tikhonov <itikhonov83@gmail.com>
 */

class RelationsMatrix
{
    /**
     * @var array
     */
    private static $socionicsCode2Name = array(
        'ESTJ' => 'Administrator',
        'ENTJ' => 'Entrepreneur',
        'ESFJ' => 'Enthusiast',
        'ENFJ' => 'Mentor',
        'ESTP' => 'Conqueror',
        'ESFP' => 'Politician',
        'ENTP' => 'Seeker',
        'ENFP' => 'Advisor',
        'ISTJ' => 'Inspector',
        'INTJ' => 'Analyst',
        'ISFJ' => 'Guardian',
        'INFJ' => 'Humanist',
        'ISTP' => 'Craftsman',
        'ISFP' => 'Peacemaker',
        'INTP' => 'Critic',
        'INFP' => 'Romantic',
    );

    /**
     * @var array
     */
    private static $socionicsCode2Num = array(
        'ESTJ' => 1,
        'ENTJ' => 2,
        'ESFJ' => 3,
        'ENFJ' => 4,
        'ESTP' => 5,
        'ESFP' => 6,
        'ENTP' => 7,
        'ENFP' => 8,
        'ISTJ' => 9,
        'INTJ' => 10,
        'ISFJ' => 11,
        'INFJ' => 12,
        'ISTP' => 13,
        'ISFP' => 14,
        'INTP' => 15,
        'INFP' => 16,
    );

    /**
     * @var array
     */
    private static $socionicsNum2Code = array(
        1 => 'ESTJ',
        2 => 'ENTJ',
        3 => 'ESFJ',
        4 => 'ENFJ',
        5 => 'ESTP',
        6 => 'ESFP',
        7 => 'ENTP',
        8 => 'ENFP',
        9 => 'ISTJ',
        10 => 'INTJ',
        11 => 'ISFJ',
        12 => 'INFJ',
        13 => 'ISTP',
        14 => 'ISFP',
        15 => 'INTP',
        16 => 'INFP',
    );

    /**
     * @var array
     */
    private static $intertypeRelationCode2Name = array(
        'Du' => 'Duality',
        'Ac' => 'Activation',
        'Sd' => 'Semi-duality',
        'Mg' => 'Mirage',
        'Mr' => 'Mirror',
        'Id' => 'Identity',
        'Cp' => 'Cooperation',
        'Cg' => 'Congenerity',
        'QI' => 'Quasi-identity',
        'Ex' => 'Extinguishment',
        'Se' => 'Super-ego',
        'Cf' => 'Conflict',
        'Rq+' => 'Requester',
        'Rq-' => 'Request recipient',
        'Sv+' => 'Supervisor',
        'Sv-' => 'Supervisee',
    );

    /**
     * @var array
     */
    private static $relationType2Num = array(
        'Du' => 1,
        'Id' => 2,
        'Ac' => 3,
        'Mr' => 4,
        'Sd' => 5,
        'Cg' => 6,
        'Cf' => 7,
        'Se' => 8,
        'QI' => 9,
        'Ex' => 10,
        'Mg' => 11,
        'Cp' => 12,
        'Rq+' => 13,
        'Rq-' => 14,
        'Sv+' => 15,
        'Sv-' => 16,
    );

    private static $relationTypeNum2Code = array(
        1 => 'Du',
        2 => 'Id',
        3 => 'Ac',
        4 => 'Mr',
        5 => 'Sd',
        6 => 'Cg',
        7 => 'Cf',
        8 => 'Se',
        9 => 'QI',
        10 => 'Ex',
        11 => 'Mg',
        12 => 'Cp',
        13 => 'Rq+',
        14 => 'Rq-',
        15 => 'Sv+',
        16 => 'Sv-',
    );

    /**
     * @var array
     */
    private static $relationsMatrix = array(
        // ENTP Id
        'ENTP' => array(
            'ENTP' => 'Id',
            'ISFP' => 'Du',
            'ESFJ' => 'Ac',
            'INTJ' => 'Mr',
            'ENFJ' => 'Rq+',
            'ISTJ' => 'Sv+',
            'ESTP' => 'Cp',
            'INFP' => 'Mg',
            'ESFP' => 'Se',
            'INTP' => 'Ex',
            'ENTJ' => 'QI',
            'ISFJ' => 'Cf',
            'ESTJ' => 'Rq-',
            'INFJ' => 'Sv-',
            'ENFP' => 'Cg',
            'ISTP' => 'Sd',
        ),
        // ISFP
        'ISFP' => array(
            'ENTP' => 'Du',
            'ISFP' => 'Id',
            'ESFJ' => 'Mr',
            'INTJ' => 'Ac',
            'ENFJ' => 'Sv+',
            'ISTJ' => 'Rq+',
            'ESTP' => 'Mg',
            'INFP' => 'Cp',
            'ESFP' => 'Ex',
            'INTP' => 'Se',
            'ENTJ' => 'Cf',
            'ISFJ' => 'QI',
            'ESTJ' => 'Sv-',
            'INFJ' => 'Rq-',
            'ENFP' => 'Sd',
            'ISTP' => 'Cg',
        ),
        // ESFJ
        'ESFJ' => array(
            'ENTP' => 'Ac',
            'ISFP' => 'Mr',
            'ESFJ' => 'Id',
            'INTJ' => 'Du',
            'ENFJ' => 'Cg',
            'ISTJ' => 'Sd',
            'ESTP' => 'Rq-',
            'INFP' => 'Sv-',
            'ESFP' => 'QI',
            'INTP' => 'Cf',
            'ENTJ' => 'Se',
            'ISFJ' => 'Ex',
            'ESTJ' => 'Cp',
            'INFJ' => 'Mg',
            'ENFP' => 'Rq+',
            'ISTP' => 'Sv+',
        ),
        // INTJ
        'INTJ' => array(
            'ENTP' => 'Mr',
            'ISFP' => 'Ac',
            'ESFJ' => 'Du',
            'INTJ' => 'Id',
            'ENFJ' => 'Sd',
            'ISTJ' => 'Cg',
            'ESTP' => 'Sv-',
            'INFP' => 'Rq-',
            'ESFP' => 'Cf',
            'INTP' => 'QI',
            'ENTJ' => 'Ex',
            'ISFJ' => 'Se',
            'ESTJ' => 'Mg',
            'INFJ' => 'Cp',
            'ENFP' => 'Sv+',
            'ISTP' => 'Rq+',
        ),
        // ENFJ
        'ENFJ' => array(
            'ENTP' => 'Rq-',
            'ISFP' => 'Sv-',
            'ESFJ' => 'Cg',
            'INTJ' => 'Sd',
            'ENFJ' => 'Id',
            'ISTJ' => 'Du',
            'ESTP' => 'Ac',
            'INFP' => 'Mr',
            'ESFP' => 'Rq+',
            'INTP' => 'Sv+',
            'ENTJ' => 'Cp',
            'ISFJ' => 'Mg',
            'ESTJ' => 'Se',
            'INFJ' => 'Ex',
            'ENFP' => 'QI',
            'ISTP' => 'Cf',
        ),
        // ISTJ
        'ISTJ' => array(
            'ENTP' => 'Sv-',
            'ISFP' => 'Rq-',
            'ESFJ' => 'Sd',
            'INTJ' => 'Cg',
            'ENFJ' => 'Du',
            'ISTJ' => 'Id',
            'ESTP' => 'Mr',
            'INFP' => 'Ac',
            'ESFP' => 'Sv+',
            'INTP' => 'Rq+',
            'ENTJ' => 'Mg',
            'ISFJ' => 'Cp',
            'ESTJ' => 'Ex',
            'INFJ' => 'Se',
            'ENFP' => 'Cf',
            'ISTP' => 'QI',
        ),
        // ESTP
        'ESTP' => array(
            'ENTP' => 'Cp',
            'ISFP' => 'Mg',
            'ESFJ' => 'Rq+',
            'INTJ' => 'Sv+',
            'ENFJ' => 'Ac',
            'ISTJ' => 'Mr',
            'ESTP' => 'Id',
            'INFP' => 'Du',
            'ESFP' => 'Cg',
            'INTP' => 'Sd',
            'ENTJ' => 'Rq-',
            'ISFJ' => 'Sv-',
            'ESTJ' => 'QI',
            'INFJ' => 'Cf',
            'ENFP' => 'Se',
            'ISTP' => 'Ex',
        ),
        // INFP
        'INFP' => array(
            'ENTP' => 'Mg',
            'ISFP' => 'Cp',
            'ESFJ' => 'Sv+',
            'INTJ' => 'Rq+',
            'ENFJ' => 'Mr',
            'ISTJ' => 'Ac',
            'ESTP' => 'Du',
            'INFP' => 'Id',
            'ESFP' => 'Sd',
            'INTP' => 'Cg',
            'ENTJ' => 'Sv-',
            'ISFJ' => 'Rq-',
            'ESTJ' => 'Cf',
            'INFJ' => 'QI',
            'ENFP' => 'Ex',
            'ISTP' => 'Se',
        ),
        // ESFP
        'ESFP' => array(
            'ENTP' => 'Se',
            'ISFP' => 'Ex',
            'ESFJ' => 'QI',
            'INTJ' => 'Cf',
            'ENFJ' => 'Rq-',
            'ISTJ' => 'Sv-',
            'ESTP' => 'Cg',
            'INFP' => 'Sd',
            'ESFP' => 'Id',
            'INTP' => 'Du',
            'ENTJ' => 'Ac',
            'ISFJ' => 'Mr',
            'ESTJ' => 'Rq+',
            'INFJ' => 'Sv+',
            'ENFP' => 'Cp',
            'ISTP' => 'Mg',
        ),
        // INTP
        'INTP' => array(
            'ENTP' => 'Ex',
            'ISFP' => 'Se',
            'ESFJ' => 'Cf',
            'INTJ' => 'QI',
            'ENFJ' => 'Sv-',
            'ISTJ' => 'Rq-',
            'ESTP' => 'Sd',
            'INFP' => 'Cg',
            'ESFP' => 'Du',
            'INTP' => 'Id',
            'ENTJ' => 'Mr',
            'ISFJ' => 'Ac',
            'ESTJ' => 'Sv+',
            'INFJ' => 'Rq+',
            'ENFP' => 'Mg',
            'ISTP' => 'Cp',
        ),
        // ENTJ
        'ENTJ' => array(
            'ENTP' => 'QI',
            'ISFP' => 'Cf',
            'ESFJ' => 'Se',
            'INTJ' => 'Ex',
            'ENFJ' => 'Cp',
            'ISTJ' => 'Mg',
            'ESTP' => 'Rq+',
            'INFP' => 'Sv+',
            'ESFP' => 'Ac',
            'INTP' => 'Mr',
            'ENTJ' => 'Id',
            'ISFJ' => 'Du',
            'ESTJ' => 'Cg',
            'INFJ' => 'Sd',
            'ENFP' => 'Rq-',
            'ISTP' => 'Sv-',
        ),
        // ISFJ
        'ISFJ' => array(
            'ENTP' => 'Cf',
            'ISFP' => 'QI',
            'ESFJ' => 'Ex',
            'INTJ' => 'Se',
            'ENFJ' => 'Mg',
            'ISTJ' => 'Cp',
            'ESTP' => 'Sv+',
            'INFP' => 'Rq+',
            'ESFP' => 'Mr',
            'INTP' => 'Ac',
            'ENTJ' => 'Du',
            'ISFJ' => 'Id',
            'ESTJ' => 'Sd',
            'INFJ' => 'Cg',
            'ENFP' => 'Sv-',
            'ISTP' => 'Rq-',
        ),
        // ESTJ
        'ESTJ' => array(
            'ENTP' => 'Rq+',
            'ISFP' => 'Sv+',
            'ESFJ' => 'Cp',
            'INTJ' => 'Mg',
            'ENFJ' => 'Se',
            'ISTJ' => 'Ex',
            'ESTP' => 'QI',
            'INFP' => 'Cf',
            'ESFP' => 'Rq-',
            'INTP' => 'Sv-',
            'ENTJ' => 'Cg',
            'ISFJ' => 'Sd',
            'ESTJ' => 'Id',
            'INFJ' => 'Du',
            'ENFP' => 'Ac',
            'ISTP' => 'Mr',
        ),
        // INFJ
        'INFJ' => array(
            'ENTP' => 'Sv+',
            'ISFP' => 'Rq+',
            'ESFJ' => 'Mg',
            'INTJ' => 'Cp',
            'ENFJ' => 'Ex',
            'ISTJ' => 'Se',
            'ESTP' => 'Cf',
            'INFP' => 'QI',
            'ESFP' => 'Sv-',
            'INTP' => 'Rq-',
            'ENTJ' => 'Sd',
            'ISFJ' => 'Cg',
            'ESTJ' => 'Du',
            'INFJ' => 'Id',
            'ENFP' => 'Mr',
            'ISTP' => 'Ac',
        ),
        // ENFP
        'ENFP' => array(
            'ENTP' => 'Cg',
            'ISFP' => 'Sd',
            'ESFJ' => 'Rq-',
            'INTJ' => 'Sv-',
            'ENFJ' => 'QI',
            'ISTJ' => 'Cf',
            'ESTP' => 'Se',
            'INFP' => 'Ex',
            'ESFP' => 'Cp',
            'INTP' => 'Mg',
            'ENTJ' => 'Rq+',
            'ISFJ' => 'Sv+',
            'ESTJ' => 'Ac',
            'INFJ' => 'Mr',
            'ENFP' => 'Id',
            'ISTP' => 'Du',
        ),
        // ISTP
        'ISTP' => array(
            'ENTP' => 'Sd',
            'ISFP' => 'Cg',
            'ESFJ' => 'Sv-',
            'INTJ' => 'Rq-',
            'ENFJ' => 'Cf',
            'ISTJ' => 'QI',
            'ESTP' => 'Ex',
            'INFP' => 'Se',
            'ESFP' => 'Mg',
            'INTP' => 'Cp',
            'ENTJ' => 'Sv+',
            'ISFJ' => 'Rq+',
            'ESTJ' => 'Mr',
            'INFJ' => 'Ac',
            'ENFP' => 'Du',
            'ISTP' => 'Id',
        ),
    );

    /**
     * @var array
     */
    private static $relationsMatrix2 = array(
        // ENTP Id
        'ENTP' => array(
            'Id' => 'ENTP',
            'Du' => 'ISFP',
            'Ac' => 'ESFJ',
            'Mr' => 'INTJ',
            'Rq+' => 'ENFJ',
            'Sv+' => 'ISTJ',
            'Cp' => 'ESTP',
            'Mg' => 'INFP',
            'Se' => 'ESFP',
            'Ex' => 'INTP',
            'QI' => 'ENTJ',
            'Cf' => 'ISFJ',
            'Rq-' => 'ESTJ',
            'Sv-' => 'INFJ',
            'Cg' => 'ENFP',
            'Sd' => 'ISTP',
        ),
        // ISFP
        'ISFP' => array(
            'Du' => 'ENTP',
            'Id' => 'ISFP',
            'Mr' => 'ESFJ',
            'Ac' => 'INTJ',
            'Sv+' => 'ENFJ',
            'Rq+' => 'ISTJ',
            'Mg' => 'ESTP',
            'Cp' => 'INFP',
            'Ex' => 'ESFP',
            'Se' => 'INTP',
            'Cf' => 'ENTJ',
            'QI' => 'ISFJ',
            'Sv-' => 'ESTJ',
            'Rq-' => 'INFJ',
            'Sd' => 'ENFP',
            'Cg' => 'ISTP',
        ),
        // ESFJ
        'ESFJ' => array(
            'Ac' => 'ENTP',
            'Mr' => 'ISFP',
            'Id' => 'ESFJ',
            'Du' => 'INTJ',
            'Cg' => 'ENFJ',
            'Sd' => 'ISTJ',
            'Rq-' => 'ESTP',
            'Sv-' => 'INFP',
            'QI' => 'ESFP',
            'Cf' => 'INTP',
            'Se' => 'ENTJ',
            'Ex' => 'ISFJ',
            'Cp' => 'ESTJ',
            'Mg' => 'INFJ',
            'Rq+' => 'ENFP',
            'Sv+' => 'ISTP',
        ),
        // INTJ
        'INTJ' => array(
            'Mr' => 'ENTP',
            'Ac' => 'ISFP',
            'Du' => 'ESFJ',
            'Id' => 'INTJ',
            'Sd' => 'ENFJ',
            'Cg' => 'ISTJ',
            'Sv-' => 'ESTP',
            'Rq-' => 'INFP',
            'Cf' => 'ESFP',
            'QI' => 'INTP',
            'Ex' => 'ENTJ',
            'Se' => 'ISFJ',
            'Mg' => 'ESTJ',
            'Cp' => 'INFJ',
            'Sv+' => 'ENFP',
            'Rq+' => 'ISTP',
        ),
        // ENFJ
        'ENFJ' => array(
            'Rq-' => 'ENTP',
            'Sv-' => 'ISFP',
            'Cg' => 'ESFJ',
            'Sd' => 'INTJ',
            'Id' => 'ENFJ',
            'Du' => 'ISTJ',
            'Ac' => 'ESTP',
            'Mr' => 'INFP',
            'Rq+' => 'ESFP',
            'Sv+' => 'INTP',
            'Cp' => 'ENTJ',
            'Mg' => 'ISFJ',
            'Se' => 'ESTJ',
            'Ex' => 'INFJ',
            'QI' => 'ENFP',
            'Cf' => 'ISTP',
        ),
        // ISTJ
        'ISTJ' => array(
            'Sv-' => 'ENTP',
            'Rq-' => 'ISFP',
            'Sd' => 'ESFJ',
            'Cg' => 'INTJ',
            'Du' => 'ENFJ',
            'Id' => 'ISTJ',
            'Mr' => 'ESTP',
            'Ac' => 'INFP',
            'Sv+' => 'ESFP',
            'Rq+' => 'INTP',
            'Mg' => 'ENTJ',
            'Cp' => 'ISFJ',
            'Ex' => 'ESTJ',
            'Se' => 'INFJ',
            'Cf' => 'ENFP',
            'QI' => 'ISTP',
        ),
        // ESTP
        'ESTP' => array(
            'Cp' => 'ENTP',
            'Mg' => 'ISFP',
            'Rq+' => 'ESFJ',
            'Sv+' => 'INTJ',
            'Ac' => 'ENFJ',
            'Mr' => 'ISTJ',
            'Id' => 'ESTP',
            'Du' => 'INFP',
            'Cg' => 'ESFP',
            'Sd' => 'INTP',
            'Rq-' => 'ENTJ',
            'Sv-' => 'ISFJ',
            'QI' => 'ESTJ',
            'Cf' => 'INFJ',
            'Se' => 'ENFP',
            'Ex' => 'ISTP',
        ),
        // INFP
        'INFP' => array(
            'Mg' => 'ENTP',
            'Cp' => 'ISFP',
            'Sv+' => 'ESFJ',
            'Rq+' => 'INTJ',
            'Mr' => 'ENFJ',
            'Ac' => 'ISTJ',
            'Du' => 'ESTP',
            'Id' => 'INFP',
            'Sd' => 'ESFP',
            'Cg' => 'INTP',
            'Sv-' => 'ENTJ',
            'Rq-' => 'ISFJ',
            'Cf' => 'ESTJ',
            'QI' => 'INFJ',
            'Ex' => 'ENFP',
            'Se' => 'ISTP',
        ),
        // ESFP
        'ESFP' => array(
            'Se' => 'ENTP',
            'Ex' => 'ISFP',
            'QI' => 'ESFJ',
            'Cf' => 'INTJ',
            'Rq-' => 'ENFJ',
            'Sv-' => 'ISTJ',
            'Cg' => 'ESTP',
            'Sd' => 'INFP',
            'Id' => 'ESFP',
            'Du' => 'INTP',
            'Ac' => 'ENTJ',
            'Mr' => 'ISFJ',
            'Rq+' => 'ESTJ',
            'Sv+' => 'INFJ',
            'Cp' => 'ENFP',
            'Mg' => 'ISTP',
        ),
        // INTP
        'INTP' => array(
            'Ex' => 'ENTP',
            'Se' => 'ISFP',
            'Cf' => 'ESFJ',
            'QI' => 'INTJ',
            'Sv-' => 'ENFJ',
            'Rq-' => 'ISTJ',
            'Sd' => 'ESTP',
            'Cg' => 'INFP',
            'Du' => 'ESFP',
            'Id' => 'INTP',
            'Mr' => 'ENTJ',
            'Ac' => 'ISFJ',
            'Sv+' => 'ESTJ',
            'Rq+' => 'INFJ',
            'Mg' => 'ENFP',
            'Cp' => 'ISTP',
        ),
        // ENTJ
        'ENTJ' => array(
            'QI' => 'ENTP',
            'Cf' => 'ISFP',
            'Se' => 'ESFJ',
            'Ex' => 'INTJ',
            'Cp' => 'ENFJ',
            'Mg' => 'ISTJ',
            'Rq+' => 'ESTP',
            'Sv+' => 'INFP',
            'Ac' => 'ESFP',
            'Mr' => 'INTP',
            'Id' => 'ENTJ',
            'Du' => 'ISFJ',
            'Cg' => 'ESTJ',
            'Sd' => 'INFJ',
            'Rq-' => 'ENFP',
            'Sv-' => 'ISTP',
        ),
        // ISFJ
        'ISFJ' => array(
            'Cf' => 'ENTP',
            'QI' => 'ISFP',
            'Ex' => 'ESFJ',
            'Se' => 'INTJ',
            'Mg' => 'ENFJ',
            'Cp' => 'ISTJ',
            'Sv+' => 'ESTP',
            'Rq+' => 'INFP',
            'Mr' => 'ESFP',
            'Ac' => 'INTP',
            'Du' => 'ENTJ',
            'Id' => 'ISFJ',
            'Sd' => 'ESTJ',
            'Cg' => 'INFJ',
            'Sv-' => 'ENFP',
            'Rq-' => 'ISTP',
        ),
        // ESTJ
        'ESTJ' => array(
            'Rq+' => 'ENTP',
            'Sv+' => 'ISFP',
            'Cp' => 'ESFJ',
            'Mg' => 'INTJ',
            'Se' => 'ENFJ',
            'Ex' => 'ISTJ',
            'QI' => 'ESTP',
            'Cf' => 'INFP',
            'Rq-' => 'ESFP',
            'Sv-' => 'INTP',
            'Cg' => 'ENTJ',
            'Sd' => 'ISFJ',
            'Id' => 'ESTJ',
            'Du' => 'INFJ',
            'Ac' => 'ENFP',
            'Mr' => 'ISTP',
        ),
        // INFJ
        'INFJ' => array(
            'Sv+' => 'ENTP',
            'Rq+' => 'ISFP',
            'Mg' => 'ESFJ',
            'Cp' => 'INTJ',
            'Ex' => 'ENFJ',
            'Se' => 'ISTJ',
            'Cf' => 'ESTP',
            'QI' => 'INFP',
            'Sv-' => 'ESFP',
            'Rq-' => 'INTP',
            'Sd' => 'ENTJ',
            'Cg' => 'ISFJ',
            'Du' => 'ESTJ',
            'Id' => 'INFJ',
            'Mr' => 'ENFP',
            'Ac' => 'ISTP',
        ),
        // ENFP
        'ENFP' => array(
            'Cg' => 'ENTP',
            'Sd' => 'ISFP',
            'Rq-' => 'ESFJ',
            'Sv-' => 'INTJ',
            'QI' => 'ENFJ',
            'Cf' => 'ISTJ',
            'Se' => 'ESTP',
            'Ex' => 'INFP',
            'Cp' => 'ESFP',
            'Mg' => 'INTP',
            'Rq+' => 'ENTJ',
            'Sv+' => 'ISFJ',
            'Ac' => 'ESTJ',
            'Mr' => 'INFJ',
            'Id' => 'ENFP',
            'Du' => 'ISTP',
        ),
        // ISTP
        'ISTP' => array(
            'Sd' => 'ENTP',
            'Cg' => 'ISFP',
            'Sv-' => 'ESFJ',
            'Rq-' => 'INTJ',
            'Cf' => 'ENFJ',
            'QI' => 'ISTJ',
            'Ex' => 'ESTP',
            'Se' => 'INFP',
            'Mg' => 'ESFP',
            'Cp' => 'INTP',
            'Sv+' => 'ENTJ',
            'Rq+' => 'ISFJ',
            'Mr' => 'ESTJ',
            'Ac' => 'INFJ',
            'Du' => 'ENFP',
            'Id' => 'ISTP',
        ),
    );

    /**
     * @var array
     */
    private static $dualRealations = array(
        'ESTJ' => 'INFJ',
        'ENTJ' => 'ISFJ',
        'ESFJ' => 'INTJ',
        'ENFJ' => 'ISTJ',
        'ESTP' => 'INFP',
        'ESFP' => 'INTP',
        'ENTP' => 'ISFP',
        'ENFP' => 'ISTP',
        'ISTJ' => 'ENFJ',
        'INTJ' => 'ESFJ',
        'ISFJ' => 'ENTJ',
        'INFJ' => 'ESTJ',
        'ISTP' => 'ENFP',
        'ISFP' => 'ENTP',
        'INTP' => 'ESFP',
        'INFP' => 'ESTP',
    );

    /**
     * @var array
     */
    private static $compatibilityWeights = array(
        'Du' => 14,
        'Id' => 13,
        'Ac' => 12,
        'Mr' => 11,
        'Sd' => 10,
        'Cg' => 9,
        'Cf' => 8,
        'Se' => 7,
        'QI' => 6,
        'Ex' => 5,
        'Mg' => 4,
        'Cp' => 3,
        'Rq+' => 2,
        'Rq-' => 2,
        'Sv+' => 1,
        'Sv-' => 1,
    );

    /**
     * @return array
     */
    public static function getSocionicsCodes()
    {
        return array_keys(RelationsMatrix::$socionicsCode2Name);
    }

    /**
     * @return array
     */
    public static function getSocionicsCodesNumbered()
    {
        return RelationsMatrix::$socionicsNum2Code;
    }

    /**
     * @return array
     */
    public static function getSocionicsCodes2()
    {
        $array = array_keys(RelationsMatrix::$socionicsCode2Name);
        $array2 = [];
        $i = 1;
        foreach ($array as $key => $value) {
            $array2[$i] = $value;
            $i++;
        }

        return $array2;
    }

    /**
     * @param string $code
     * @return int
     */
    public static function getSocionicsCodeNum($code)
    {
        return RelationsMatrix::$socionicsCode2Num[$code];
    }

    /**
     * @param int $num
     * @return string
     */
    public static function getSocionicsNumCode($num)
    {
        return RelationsMatrix::$socionicsNum2Code[$num];
    }

    /**
     * @param string $code
     * @return string
     */
    public static function getSocionicsCodeName($code)
    {
        $code = mb_strtoupper($code);

        return RelationsMatrix::$socionicsCode2Name[$code];
    }

    /**
     * @param string $code
     * @return string
     */
    public static function getSocionicsDescriptionLink($code)
    {
        return mb_strtolower($code);
    }

    /**
     * @param string $code
     * @return string
     */
    public static function getIntertypeRelationCodeName($code)
    {
        return RelationsMatrix::$intertypeRelationCode2Name[$code];
    }

    /**
     * @param string $code
     * @return string
     */
    public static function getIntertypeRelationDescriptionLink($code)
    {
        if ($code == 'Rq+') {
            return 'rqplus';
        }

        if ($code == 'Rq-') {
            return 'rqminus';
        }

        if ($code == 'Sv+') {
            return 'svplus';
        }

        if ($code == 'Sv-') {
            return 'svminus';
        }

        return mb_strtolower($code);
    }

    /**
     * @param string $code
     * @return array
     */
    public static function getIntertypeRelations($code)
    {
        return RelationsMatrix::$relationsMatrix[$code];
    }

    /**
     * @param int $num1
     * @param int $num2
     * @return string
     */
    public static function getIntertypeRelation($num1, $num2)
    {
        $code1 = RelationsMatrix::$socionicsNum2Code[$num1];
        $code2 = RelationsMatrix::$socionicsNum2Code[$num2];
        $result = RelationsMatrix::$relationsMatrix[$code1][$code2];

        return $result;
    }

    /**
     * @param int $num1
     * @param int $num2
     * @return int
     */
    public static function getIntertypeRelationWeight($num1, $num2)
    {
        $name = RelationsMatrix::getIntertypeRelation($num1, $num2);

        return RelationsMatrix::$compatibilityWeights[$name];
    }

    /**
     * @param int $num1
     * @return int
     */
    public static function getDualRelationCode($num1)
    {
        $code1 = RelationsMatrix::$socionicsNum2Code[$num1];
        $code2 = RelationsMatrix::$dualRealations[$code1];
        $num2 = RelationsMatrix::getSocionicsCodeNum($code2);

        return $num2;
    }

    /**
     * @param string $code
     * @return int
     */
    public static function getRelationTypeNum($code)
    {
        return RelationsMatrix::$relationType2Num[$code];
    }

    /**
     * @param int $num
     * @return string
     */
    public static function getRelationTypeCode($num)
    {
        return RelationsMatrix::$relationTypeNum2Code[$num];
    }

    /**
     * @param int $num
     * @param int $type
     * @return array
     */
    public static function getSocionicsNums4RelationsType($num, $type)
    {
        $socionicsCode = RelationsMatrix::$socionicsNum2Code[$num];
        $result = [];
        for ($i = 1; $i <= $type; $i++) {
            $typeCode = RelationsMatrix::$relationTypeNum2Code[$i];
            $resultCode = RelationsMatrix::$relationsMatrix2[$socionicsCode][$typeCode];
            $result[] = RelationsMatrix::$socionicsCode2Num[$resultCode];
        }

        return $result;
    }
}