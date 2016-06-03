<?php
/**
 * Yet Another Dating Site based on socionics
 * PHP, Linux, Apache, Nginx, Twig, MongoDB, Redis, RabbitMQ, AJAX, JQuery, JSON
 *
 * @author Igor Tikhonov <itikhonov83@gmail.com>
 */

ini_set('error_reporting', E_ALL);

/**
 * Class Core
 */
class Core
{
    /**
     * @var bool
     */
    public static $landingMode = false;

    /**
     * @var array
     */
    public $args = [];

    /**
     * @var Twig_Environment
     */
    private $twig;

    public function __construct()
    {
        $this->args['landingMode'] = Core::$landingMode;
        $this->args['url'] = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];
        $this->args['loggedIn'] = isset($_SESSION['userId']);
        $this->args['registerLink'] = Core::$landingMode ? '/' : '/register';
        $this->args['showNewMessages'] = false;

        // Twig initialization
        $loader = new Twig_Loader_Filesystem($_SERVER['DOCUMENT_ROOT'] . '/templates');
        $this->twig = new Twig_Environment($loader, array(
            'cache' => '/tmp/cache',
            'debug' => true,
        ));
        $this->twig->addExtension(new Twig_Extensions_Extension_I18n());

        // Locale initialization
        // NOTE: set up language support in OS
        // For Ubuntu
        // $ sudo apt-get install php5-intl
        // $ sudo apt-get install language-pack-ru_RU
        // $ sudo service apache2 restart
        //
        // For CentOS
        // $ yum groupinstall russian-support
        // $ yum langinstall ru_RU
        if (!isset($_SESSION['userId']) && !isset($_SESSION['lang'])) {
            $_SESSION['lang'] = Core::lang2lang(Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE']));
        } else if (isset($_SESSION['userId'])) {
            $_SESSION['lang'] = Member::getLangById($_SESSION['userId']);
        }

        switch ($_SESSION['lang']) {
            case 'ru':
                $this->args['lang'] = 'ru';
                $lang = 'ru_RU';
                $codeset = 'utf8';
                $locale = $lang . '.' . $codeset;
                break;

            default:
                $this->args['lang'] = 'en';
                $lang = 'en_US';
                $codeset = 'utf8';
                $locale = $lang . '.' . $codeset;
                break;
        }

        setlocale(LC_ALL, $locale);
        bindtextdomain('messages', $_SERVER['DOCUMENT_ROOT'] . '/locale');
        textdomain('messages');
        bind_textdomain_codeset('messages', 'UTF-8');
    }

    /**
     * @return bool
     */
    public function isLoggedIn()
    {
        return isset($_SESSION['userId']);
    }

    /**
     * @param array $pages
     */
    public function redirectWoAuth2Index($pages)
    {
        if (!isset($_SESSION['userId'])) {
            if (in_array($_REQUEST['what'], $pages)) {
                $this->redirect2Index();
            }
        }
    }

    /**
     * @param string $lang
     * @return string
     */
    public static function lang2lang($lang)
    {
        $lang = strtolower($lang);

        // English
        if ($lang == strtolower('en_AS')) {
            return 'en';
        }

        if ($lang == strtolower('en_AU')) {
            return 'en';
        }

        if ($lang == strtolower('en_BB')) {
            return 'en';
        }

        if ($lang == strtolower('en_BE')) {
            return 'en';
        }

        if ($lang == strtolower('en_BM')) {
            return 'en';
        }

        if ($lang == strtolower('en_BW')) {
            return 'en';
        }

        if ($lang == strtolower('en_BZ')) {
            return 'en';
        }

        if ($lang == strtolower('en_CA')) {
            return 'en';
        }

        if ($lang == strtolower('en_GB')) {
            return 'en';
        }

        if ($lang == strtolower('en_GU')) {
            return 'en';
        }

        if ($lang == strtolower('en_GY')) {
            return 'en';
        }

        if ($lang == strtolower('en_HK')) {
            return 'en';
        }

        if ($lang == strtolower('en_US')) {
            return 'en';
        }

        if ($lang == strtolower('en_IE')) {
            return 'en';
        }

        if ($lang == strtolower('en_IN')) {
            return 'en';
        }

        if ($lang == strtolower('en_JM')) {
            return 'en';
        }

        if ($lang == strtolower('en_MH')) {
            return 'en';
        }

        if ($lang == strtolower('en_MP')) {
            return 'en';
        }

        if ($lang == strtolower('en_MT')) {
            return 'en';
        }

        if ($lang == strtolower('en_MU')) {
            return 'en';
        }

        if ($lang == strtolower('en_NA')) {
            return 'en';
        }

        if ($lang == strtolower('en_NZ')) {
            return 'en';
        }

        if ($lang == strtolower('en_PH')) {
            return 'en';
        }

        if ($lang == strtolower('en_PK')) {
            return 'en';
        }

        if ($lang == strtolower('en_SG')) {
            return 'en';
        }

        if ($lang == strtolower('en_TT')) {
            return 'en';
        }

        if ($lang == strtolower('en_UM')) {
            return 'en';
        }

        if ($lang == strtolower('en_US')) {
            return 'en';
        }

        if ($lang == strtolower('en_US_POSIX')) {
            return 'en';
        }

        if ($lang == strtolower('en_VI')) {
            return 'en';
        }

        if ($lang == strtolower('en_ZA')) {
            return 'en';
        }

        if ($lang == strtolower('en_ZW')) {
            return 'en';
        }

        // Russian
        if ($lang == strtolower('ru_MD')) {
            return 'ru';
        }

        if ($lang == strtolower('ru_RU')) {
            return 'ru';
        }

        if ($lang == strtolower('ru_UA')) {
            return 'ru';
        }

        if ($lang == strtolower('be')) {
            return 'ru';
        }

        if ($lang == strtolower('be_BY')) {
            return 'ru';
        }

        if ($lang == strtolower('kk')) {
            return 'ru';
        }

        if ($lang == strtolower('kk_Cyrl')) {
            return 'ru';
        }

        if ($lang == strtolower('kk_Cyrl_KZ')) {
            return 'ru';
        }

        return $lang;
    }

    public static function redirect2Index()
    {
        header('Location: ' . $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/');
    }

    public static function redirect2Profile()
    {
        if (!Core::$landingMode) {
            header('Location: ' . $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/profile');
        } else {
            Core::redirect2Thanks();
        }
    }

    public static function redirect2Thanks()
    {
        if (Core::$landingMode) {
            header('Location: ' . $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/thanks');
        } else {
            Core::redirect2Profile();
        }
    }

    public static function redirect2Saved()
    {
        if (!Core::$landingMode) {
            header('Location: ' . $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/saved');
        } else {
            Core::redirect2Thanks();
        }
    }

    public static function redirect2TestResults()
    {
        header('Location: ' . $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/results');
    }

    public static function redirect2Email()
    {
        header('Location: ' . $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/email');
    }

    public static function redirect2Login() {
        header('Location: ' . $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/login');
    }

    public static function redirect2View($id) {
        header('Location: ' . $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/profile/view/' . $id);
    }

    /**
     * @param string $template
     */
    public function render($template)
    {
        // Page rendering
        $template = $this->twig->loadTemplate($template . '.html');
        echo $template->render($this->args);
    }

    //
    // About pages rendering
    //
    public function renderAboutIndex()
    {
        $this->render('about/index');
    }

    public function renderAboutHowItWorks()
    {
        $this->render('about/howitworks');
    }

    public function renderAboutWhyItWorks()
    {
        $this->render('about/whyitworks');
    }

    public function renderAboutDetailed()
    {
        $this->render('about/detailed');
    }

    public function renderAboutOptions()
    {
        $this->render('about/options');
    }

    //
    // Articles pages rendering
    //
    public function renderArticlesIndex()
    {
        $this->render('articles/index');
    }

    public function renderArticle()
    {
        $this->render('articles/article');
    }

    //
    // Langing pages rendering
    //
    public function renderLandingIndex()
    {
        $this->render('landing/index');
    }

    public function renderLandingThanks()
    {
        $this->render('landing/thanks');
    }

    public function renderLandingRelations()
    {
        $this->render('landing/relations');
    }

    //
    // Messages pages rendering
    //
    public function renderMessagesIndex()
    {
        $this->args['showNewMessages'] = true;
        $this->render('messages/index');
    }

    public function  renderMessagesSend()
    {
        $configuration = Configuration::getInstance();
        $this->args['checkForMessagesInterval'] = $configuration->checkForMessagesInterval;
        $this->render('messages/send');
    }

    //
    // Profile pages rendering
    //
    public function renderProfileLogin()
    {
        $this->render('profile/login');
    }

    public function renderProfileInvite()
    {
        $this->render('profile/invite');
    }

    public function renderProfileIndex()
    {
        $configuration = Configuration::getInstance();
        $this->args['showNewMessages'] = true;
        $this->args['checkForMessagesInterval'] = $configuration->checkForMessagesInterval;
        $this->render('profile/index');
    }

    public function renderProfileView()
    {
        $this->render('profile/view');
    }

    public function renderProfileAnalytics()
    {
        $this->render('profile/analytics');
    }

    public function renderProfileRestore()
    {
        $this->render('profile/restore');
    }

    public function renderProfileUnsubscribe()
    {
        $this->render('profile/unsubscribe');
    }

    public function renderProfileEmail()
    {
        $this->render('profile/email');
    }

    //
    // Purchase page rendering
    //
    public function renderPurchaseIndex()
    {
        $this->render('purchase/index');
    }

    //
    // Register
    //
    public function renderRegisterIndex()
    {
        $this->render('register/index');
    }

    //
    // Relations pages rendering
    //
    public function renderRelationsIndex()
    {
        $this->render('relations/index');
    }

    public function renderRelationsIRTypes()
    {
        $this->render('relations/irtypes');
    }

    public function renderRelationsTypes()
    {
        $this->render('relations/types');
    }

    //
    // Report
    //
    public function renderReportIndex()
    {
        $this->render('report/index');
    }

    //
    // Search pages rendering
    //
    public function renderSearchIndex()
    {
        $this->render('search/index');
    }

    public function renderSearchSaved()
    {
        $this->render('search/saved');
    }

    public function renderSearchEdit()
    {
        $this->render('search/edit');
    }

    public function renderSearchRun()
    {
        $this->render('search/run');
    }

    //
    // Test pages rendering
    //
    public function renderTestInstructions()
    {
        $this->render('test/instructions');
    }

    public function renderTestIndex()
    {
        $this->render('test/index');
    }

    public function renderTestResults()
    {
        $this->render('test/results');
    }
}