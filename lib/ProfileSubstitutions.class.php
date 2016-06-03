<?php
/**
 * Yet Another Dating Site based on socionics
 * PHP, Linux, Apache, Nginx, Twig, MongoDB, Redis, RabbitMQ, AJAX, JQuery, JSON
 *
 * @author Igor Tikhonov <itikhonov83@gmail.com>
 */

class ProfileSubstitutions
{
    const ANY = 0;
    const NO = 'no';

    const EDUCATION_Primary_school = 1;
    const EDUCATION_Middle_school = 2;
    const EDUCATION_High_school = 3;
    const EDUCATION_College = 4;
    const EDUCATION_Bachelor = 5;
    const EDUCATION_Masters = 6;
    const EDUCATION_PhD = 7;

    const BMI_less_than_16 = 1;
    const BMI_16_18_5 = 2;
    const BMI_18_5_25 = 3;
    const BMI_25_30 = 4;
    const BMI_30_35 = 5;
    const BMI_35_40 = 6;
    const BMI_over_40 = 7;

    /**
     * @var array
     */
    public $hairColor = [];

    /**
     * @var array
     */
    public $hairLength = [];

    /**
     * @var array
     */
    public $hairType = [];

    /**
     * @var array
     */
    public $eyeColor = [];

    /**
     * @var array
     */
    public $eyeWear = [];

    /**
     * @var array
     */
    public $BMI = [];

    /**
     * @var array
     */
    public $bodyType = [];

    /**
     * @var array
     */
    public $ethnicity = [];

    /**
     * @var array
     */
    public $bodyArt = [];

    /**
     * @var array
     */
    public $drinking = [];

    /**
     * @var array
     */
    public $smoking = [];

    /**
     * @var array
     */
    public $maritalStatus = [];

    /**
     * @var array
     */
    public $kids = [];

    /**
     * @var array
     */
    public $wantMoreKids = [];

    /**
     * @var array
     */
    public $employmentStatus = [];

    /**
     * @var array
     */
    public $field = [];

    /**
     * @var array
     */
    public $annualIncome = [];

    /**
     * @var array
     */
    public $residence = [];

    /**
     * @var array
     */
    public $religion = [];
    /**
     * @var array
     */
    public $education = [];

    /**
     * @var array
     */
    public $willing2Relocate = [];

    public function __construct() {
        // Education
        $this->education[ProfileSubstitutions::EDUCATION_Primary_school] = 'Primary school';
        $this->education[ProfileSubstitutions::EDUCATION_Middle_school] = 'Middle school';
        $this->education[ProfileSubstitutions::EDUCATION_High_school] = 'High school';
        $this->education[ProfileSubstitutions::EDUCATION_College] = 'College';
        $this->education[ProfileSubstitutions::EDUCATION_Bachelor] = 'Bachelor';
        $this->education[ProfileSubstitutions::EDUCATION_Masters] = 'Masters';
        $this->education[ProfileSubstitutions::EDUCATION_PhD] = 'PhD';

        // BMI
        $this->BMI[ProfileSubstitutions::BMI_less_than_16] = 'less than 16';
        $this->BMI[ProfileSubstitutions::BMI_16_18_5] = '16-18.5';
        $this->BMI[ProfileSubstitutions::BMI_18_5_25] = '18.5-25';
        $this->BMI[ProfileSubstitutions::BMI_25_30] = '25-30';
        $this->BMI[ProfileSubstitutions::BMI_30_35] = '30-35';
        $this->BMI[ProfileSubstitutions::BMI_35_40] = '35-40';
        $this->BMI[ProfileSubstitutions::BMI_over_40] = 'over 40';

        // Hair color
        $this->hairColor[1] = 'black';
        $this->hairColor[2] = 'blonde';
        $this->hairColor[3] = 'brown';
        $this->hairColor[4] = 'red';
        $this->hairColor[5] = 'light brown';
        $this->hairColor[6] = 'bald';
        $this->hairColor[7] = 'grey';
        $this->hairColor[8] = 'changes frequently';

        // Hair length
        $this->hairLength[1] = 'short';
        $this->hairLength[2] = 'shaved';
        $this->hairLength[3] = 'medium';
        $this->hairLength[4] = 'long';
        $this->hairLength[5] = 'bald';

        // Hair type
        $this->hairType[1] = 'curly';
        $this->hairType[2] = 'straight';
        $this->hairType[3] = 'wavy';
        $this->hairType[4] = 'other';

        // Eye color
        $this->eyeColor[1] = 'brown_3';
        $this->eyeColor[2] = 'hazel';
        $this->eyeColor[3] = 'black_3';
        $this->eyeColor[4] = 'green';
        $this->eyeColor[5] = 'blue';
        $this->eyeColor[6] = 'grey_2';

        // Eye wear
        $this->eyeWear[1] = 'none';
        $this->eyeWear[2] = 'contacts';
        $this->eyeWear[3] = 'glasses';

        // Body type
        $this->bodyType[1] = 'athletic';
        $this->bodyType[2] = 'full figured';
        $this->bodyType[3] = 'petite';
        $this->bodyType[4] = 'average';
        $this->bodyType[5] = 'large and lovely';
        $this->bodyType[6] = 'slim';
        $this->bodyType[7] = 'few extra pounds';

        // Ethnicity
        $this->ethnicity[1] = 'black_4';
        $this->ethnicity[2] = 'indian';
        $this->ethnicity[3] = 'arab (Mid East)';
        $this->ethnicity[4] = 'caucasian (white)';
        $this->ethnicity[5] = 'mixed';
        $this->ethnicity[6] = 'asian';
        $this->ethnicity[7] = 'hispanic / latino';
        $this->ethnicity[8] = 'pacific islander';
        $this->ethnicity[9] = 'other_2';

        // Body art
        $this->bodyArt[1] = 'none_2';
        $this->bodyArt[2] = 'piercing';
        $this->bodyArt[3] = 'branding';
        $this->bodyArt[4] = 'tattoo';
        $this->bodyArt[5] = 'earrings';
        $this->bodyArt[6] = 'prefer not to say';
        $this->bodyArt[7] = 'other';

        // Drinking
        $this->drinking[1] = 'occasionally';
        $this->drinking[2] = 'drink';
        $this->drinking[3] = 'don\'t drink';

        // Smoking
        $this->smoking[1] = 'occasionally';
        $this->smoking[2] = 'smoke';
        $this->smoking[3] = 'don\'t smoke';

        // Marital status
        $this->maritalStatus[1] = 'single';
        $this->maritalStatus[2] = 'married';
        $this->maritalStatus[3] = 'separated';
        $this->maritalStatus[4] = 'divorced';
        $this->maritalStatus[5] = 'widowed';

        // Kids
        $this->kids[1] = 'no';
        $this->kids[2] = 'yes, live at home';
        $this->kids[3] = 'yes, sometimes live at home';
        $this->kids[4] = 'yes, don\'t live at home';

        // Want more kids
        $this->wantMoreKids[1] = 'yes';
        $this->wantMoreKids[2] = 'no';
        $this->wantMoreKids[3] = 'not sure';

        // Employment status
        $this->employmentStatus[1] = 'full time';
        $this->employmentStatus[2] = 'not employed';
        $this->employmentStatus[3] = 'student';
        $this->employmentStatus[4] = 'homemaker';
        $this->employmentStatus[5] = 'part time';
        $this->employmentStatus[6] = 'retired';
        $this->employmentStatus[7] = 'other';

        // Field
        $this->field[1] = 'Artistic / Creative / Performance';
        $this->field[2] = 'Education / Academic';
        $this->field[3] = 'Farming / Agriculture';
        $this->field[4] = 'Hair Dresser / Personal Grooming';
        $this->field[5] = 'Legal';
        $this->field[6] = 'Nanny / Child care';
        $this->field[7] = 'Political / Govt / Civil Service';
        $this->field[8] = 'Sales / Marketing';
        $this->field[9] = 'Student';
        $this->field[10] = 'Travel / Hospitality';
        $this->field[11] = 'Administrative / Secretarial / Clerical';
        $this->field[12] = 'Construction / Trades';
        $this->field[13] = 'Entertainment / Media';
        $this->field[14] = 'Finance / Banking / Real Estate';
        $this->field[15] = 'IT / Communications';
        $this->field[16] = 'Medical / Dental / Veterinary';
        $this->field[17] = 'No occupation / Stay at home';
        $this->field[18] = 'Retail / Food services';
        $this->field[19] = 'Self Employed';
        $this->field[20] = 'Technical / Science / Engineering';
        $this->field[21] = 'Unemployed';
        $this->field[22] = 'Advertising / Media';
        $this->field[23] = 'Domestic Helper';
        $this->field[24] = 'Executive / Management / HR';
        $this->field[25] = 'Fire / Law enforcement / Security';
        $this->field[26] = 'Laborer / Manufacturing';
        $this->field[27] = 'Military';
        $this->field[28] = 'Non-profit / Clergy / Social services';
        $this->field[29] = 'Retired';
        $this->field[30] = 'Sports / Recreation';
        $this->field[31] = 'Transportation';
        $this->field[32] = 'Other_3';

        // Annual income
        $this->annualIncome[1] = 'less than $20k';
        $this->annualIncome[2] = '$20-30k';
        $this->annualIncome[3] = '$30-40k';
        $this->annualIncome[4] = '$40-50k';
        $this->annualIncome[5] = '$50-60k';
        $this->annualIncome[6] = '$60-70k';
        $this->annualIncome[7] = '$70-80k';
        $this->annualIncome[8] = '$80-90k';
        $this->annualIncome[9] = 'over $100k';

        // Residence
        $this->residence[1] = 'farm';
        $this->residence[2] = 'apartment / flat';
        $this->residence[3] = 'house';
        $this->residence[4] = 'condominium';
        $this->residence[5] = 'town house';
        $this->residence[6] = 'prefer not to say';
        $this->residence[7] = 'other';

        // Religion
        $this->religion[1] = 'christian - catholic';
        $this->religion[2] = 'christian - protestant';
        $this->religion[3] = 'christian - orthodox';
        $this->religion[4] = 'christian - other';
        $this->religion[5] = 'jainism';
        $this->religion[6] = 'shintoism';
        $this->religion[7] = 'Bahai';
        $this->religion[8] = 'hindu';
        $this->religion[9] = 'jewish';
        $this->religion[10] = 'sikhism';
        $this->religion[11] = 'buddhist';
        $this->religion[12] = 'islam';
        $this->religion[13] = 'parsi';
        $this->religion[14] = 'taoism';
        $this->religion[15] = 'other_3';
        $this->religion[16] = 'no religion';

        // Willing to relocate
        $this->willing2Relocate[1] = 'not willing to relocate';
        $this->willing2Relocate[2] = 'not sure about relocating';
        $this->willing2Relocate[3] = 'willing to relocate within my country';
        $this->willing2Relocate[4] = 'willing to relocate to another country';
    }
}