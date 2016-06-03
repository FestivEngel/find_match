<?php
/**
 * Yet Another Dating Site based on socionics
 * PHP, Linux, Apache, Nginx, Twig, MongoDB, Redis, RabbitMQ, AJAX, JQuery, JSON
 *
 * @author Igor Tikhonov <itikhonov83@gmail.com>
 */

header('Content-Type: application/json');

require_once dirname(__FILE__) . '/../lib/autoload.php';

$result['status'] = true;
$savedSearch = new SavedSearch();
if (isset($_SESSION['userId']) && isset($_REQUEST['command'])) {
    switch ($_REQUEST['command']) {
        case 'store':
            $savedSearch->setAll();
            $savedSearch->saved = true;
            $savedSearch->store();
            $result['id'] = $savedSearch->id->{'$id'};
            break;

        case 'setSaved':
            if (isset($_REQUEST['searchId'])) {
                SavedSearch::setSaved($_REQUEST['searchId'], $_REQUEST['name']);
            }
            break;

        case 'runNew':
            $savedSearch->setAll();
            $savedSearch->store();
            $result['id'] = $savedSearch->id->{'$id'};
            break;

        case 'runRedefined':
            $savedSearch->setAll();
            $savedSearch->update();
            $result['id'] = $_REQUEST['id'];
            break;

        case 'update':
            if (isset($_REQUEST['id'])) {
                $savedSearch->setAll();
                $savedSearch->update();
                $result['id'] = $_REQUEST['id'];
            }
            break;

        case 'delete':
            if (isset($_REQUEST['id'])) {
                $savedSearch->userId = $_SESSION['userId'];
                $savedSearch->id = $_REQUEST['id'];
                $savedSearch->delete();
            }
            break;

        case 'add2Favorites':
            if (isset($_REQUEST['id'])) {
                $favorite = new Favorite();
                $favorite->userId = $_SESSION['userId'];
                $favorite->anotherUserId = $_REQUEST['id'];
                $favorite->store();
                // Mark as viewed
                $profileView = new ProfileView();
                $profileView->userId = $_SESSION['userId'];
                $profileView->anotherUserId = $_REQUEST['id'];
                $profileView->store();
            }
            break;

        case 'markAsViewed':
            if (isset($_REQUEST['id'])) {
                $profileView = new ProfileView();
                $profileView->userId = $_SESSION['userId'];
                $profileView->anotherUserId = $_REQUEST['id'];
                $profileView->store();
            }
            break;

        case 'markAsUnviewed':
            if (isset($_REQUEST['id'])) {
                $profileView = new ProfileView();
                $profileView->userId = $_SESSION['userId'];
                $profileView->anotherUserId = $_REQUEST['id'];
                $profileView->delete();
            }
            break;

        case 'deleteFavorite':
            if (isset($_REQUEST['id'])) {
                $favorite = new Favorite();
                $favorite->userId = $_SESSION['userId'];
                $favorite->anotherUserId = $_REQUEST['id'];
                $favorite->delete();
            }
            break;

        default:
            $result['status'] = false;
    }
}

echo json_encode($result);