<?php
/**
 * Yet Another Dating Site based on socionics
 * PHP, Linux, Apache, Nginx, Twig, MongoDB, Redis, RabbitMQ, AJAX, JQuery, JSON
 *
 * @author Igor Tikhonov <itikhonov83@gmail.com>
 */

require_once dirname(__FILE__) . '/lib/autoload.php';
require_once dirname(__FILE__) . '/vendor/autoload.php';

if (isset($_REQUEST['login'])) {
    $loginForm = new LoginForm();
    if ($loginForm->process()) {
        Core::redirect2Profile();
    }
}

$core = new Core();
$core->redirectWoAuth2Index(array('saved', 'new', 'edit'));

switch ($_REQUEST['what']) {
    case 'saved':
        $savedSearchesList = new SavedSearchesList();
        $savedSearchesList->load($_SESSION['userId']);
        $core->args['savedSearches'] = $savedSearchesList->data;
        $favoritesList = new FavoritesList();
        $favoritesList->userId = $_SESSION['userId'];
        $favoritesList->load();
        $core->args['favorites'] = $favoritesList->favorites;
        $core->renderSearchSaved();
        break;

    case 'new':
        $savedSearch = new SavedSearch();
        $core->args['savedSearch'] = $savedSearch;
        $core->args['socionicsCodes'] = RelationsMatrix::getSocionicsCodesNumbered();
        $profileSubstitutions = new ProfileSubstitutions();
        $core->args['substitutions'] = $profileSubstitutions;
        $core->args['command'] = 'store';

        // Countries
        $core->args['livingCountries'] = Living::$countries;

        // Cities
        $core->args['citiesUnitedStates'] = Living::$citiesUnitedStates;
        $core->args['citiesCanada'] = Living::$citiesCanada;
        $core->args['citiesRussia'] = Living::$citiesRussia;
        $core->args['citiesUkraine'] = Living::$citiesUkraine;
        $core->args['citiesKazakhstan'] = Living::$citiesKazakhstan;
        $core->args['citiesBelarus'] = Living::$citiesBelarus;

        // Render
        $core->renderSearchEdit();
        break;

    case 'edit':
        if (isset($_REQUEST['searchId'])) {
            $savedSearch = new SavedSearch();
            $savedSearch->id = $_REQUEST['searchId'];
            $savedSearch->load();
            $core->args['savedSearch'] = $savedSearch;
            $core->args['socionicsCodes'] = RelationsMatrix::getSocionicsCodesNumbered();
            $profileSubstitutions = new ProfileSubstitutions();
            $core->args['substitutions'] = $profileSubstitutions;
            $core->args['command'] = 'update';

            // Countries
            $core->args['livingCountries'] = Living::$countries;

            // Cities
            $core->args['citiesUnitedStates'] = Living::$citiesUnitedStates;
            $core->args['citiesCanada'] = Living::$citiesCanada;
            $core->args['citiesRussia'] = Living::$citiesRussia;
            $core->args['citiesUkraine'] = Living::$citiesUkraine;
            $core->args['citiesKazakhstan'] = Living::$citiesKazakhstan;
            $core->args['citiesBelarus'] = Living::$citiesBelarus;

            // Render
            $core->renderSearchEdit();
        } else {
            Core::redirect2Saved();
        }
        break;

    case 'run':
        $searchForm = new SearchForm();
        if (isset($_REQUEST['search']) && $searchForm->process()) {
            $core->args['paid'] = Member::getPaidById($_SESSION['userId']);
            $core->args['foundMembers'] = $searchForm->search->foundMembers;
            $core->renderSearchRun();
            break;
        }

        if (isset ($_SESSION['userId']) && isset($_REQUEST['searchId'])) {
            $savedSearch = new SavedSearch();
            $core->args['paid'] = Member::getPaidById($_SESSION['userId']);
            if ($_REQUEST['searchId'] == 'default') {
                $savedSearch->userId = $_SESSION['userId'];
                $savedSearch->loadDefault();
                $advancedSearch = new AdvancedSearch();
                $advancedSearch->savedSearch = $savedSearch;
                $advancedSearch->run();
                $core->args['foundMembers'] = $advancedSearch->foundMembers;
                $core->renderSearchRun();
                break;
            } else {
                $savedSearch->id = $_REQUEST['searchId'];
                if ($savedSearch->load() && $savedSearch->isForCurrentUser()) {
                    $core->args['saved'] = $savedSearch->saved;
                    $advancedSearch = new AdvancedSearch();
                    $advancedSearch->savedSearch = $savedSearch;
                    $advancedSearch->run();
                    $core->args['foundMembers'] = $advancedSearch->foundMembers;
                    if (!$savedSearch->saved) {
                        $core->args['searchId'] = $_REQUEST['searchId'];
                        $core->args['searchName'] = $savedSearch->name;
                    }

                    $core->renderSearchRun();
                    break;
                }
            }
        }

        $core->renderSearchIndex();
        break;

    default:
        // Countries
        $core->args['livingCountries'] = Living::$countries;

        // Cities
        $core->args['citiesUnitedStates'] = Living::$citiesUnitedStates;
        $core->args['citiesCanada'] = Living::$citiesCanada;
        $core->args['citiesRussia'] = Living::$citiesRussia;
        $core->args['citiesUkraine'] = Living::$citiesUkraine;
        $core->args['citiesKazakhstan'] = Living::$citiesKazakhstan;
        $core->args['citiesBelarus'] = Living::$citiesBelarus;

        $defaultSearch = new SavedSearch();
        $defaultSearch->userId = $_SESSION['userId'];
        if (isset($_SESSION['userId']) && $defaultSearch->loadDefault()) {
            $core->args['setDefaults'] = true;
            $core->args['defaultSearch'] = $defaultSearch;
        } else {
            $core->args['setDefaults'] = false;
        }

        // Render
        $core->renderSearchIndex();
        break;
}