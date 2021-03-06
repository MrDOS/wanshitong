<?php

/**
 * W A N   S H I   T O N G
 *
 * A bookstore web interface.
 *
 * @author Kate-Lynn MacPhail, 100096539
 * @author Nicholas Wetmore, 100104702
 * @author Samuel Coleman, 100105709
 */

$starttime = microtime(true);

/* Load in site configuration and register the autoloader. */
require_once 'config.php';
require_once 'engine/wanshitong/Autoloader.php';
spl_autoload_register(array(new \wanshitong\Autoloader(__DIR__ . DIRECTORY_SEPARATOR . 'engine'), 'load'));

/* Set up the template manager. */
\wanshitong\TemplateManager::setDefaultTemplateManager(new \wanshitong\TemplateManager(__DIR__ . DIRECTORY_SEPARATOR . 'templates'));

/* Connect to the database. */
$db = new PDO('mysql:dbname=' . DB_NAME . ';host=' . DB_HOST, DB_USER, DB_PASS);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

/* We're pretty much guaranteed to need a session for cart and login
 * functionality. */
session_start();

/* Use statements to make the routes a little more clear. */
use \wanshitong\models\Authors;
use \wanshitong\models\Books;
use \wanshitong\models\Departments;
use \wanshitong\models\Orders;
use \wanshitong\models\Staff;
use \wanshitong\models\Students;
use \wanshitong\Router;

/* Configure routes. */
Router::route(array(
    /* Public routes. */
    '/' => function() use ($db) {
            return new \wanshitong\controllers\MessageController('Welcome', 'We have books! They are for sale! For money!');
        },
    '/books/?' => function() use ($db) {
            return new \wanshitong\controllers\BooksController(new Books($db), new Departments($db), new Authors($db));
        },
    '/books/student/?' => function() use ($db) {
            return new \wanshitong\controllers\StudentBooksController(new Books($db), new Departments($db), new Authors($db));
        },
    '/cart/?' => function() use ($db) {
            return new \wanshitong\controllers\CartController(new Books($db), new Orders($db), new Students($db));
        },
    '/orders/?' => function() use ($db) {
            return new \wanshitong\controllers\OrdersController(new Orders($db));
        },
    '/order/([0-9]+)/?' => array(function() use ($db) {
            return new \wanshitong\controllers\OrderController(new Orders($db), new Students($db), new Books($db));
        }, array('order')),
    /* Staff-only. */
    '/inventory/?' => function() use ($db) {
            return new \wanshitong\controllers\InventoryController(new Books($db), new Departments($db), new Authors($db));
        },
    '/management/?' => function() use ($db) {
            return new \wanshitong\controllers\ManagementController(new Books($db), new Departments($db), new Authors($db));
        },
    '/login/?' => function() use ($db) {
            return new \wanshitong\controllers\LoginController(new Staff($db));
        },
    '/logout/?' => function() use ($db) {
            return new \wanshitong\controllers\LogoutController();
        },
    '.*' => new \wanshitong\controllers\ErrorController()
));

printf("\n<!-- Page generated in %0.6fs. -->", microtime(true) - $starttime);