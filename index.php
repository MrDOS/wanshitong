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

/* Use statements to make the routes a little more clear. */
use \wanshitong\controllers\BooksController;
use \wanshitong\controllers\ErrorController;
use \wanshitong\controllers\MessageController;
use \wanshitong\models\Books;
use \wanshitong\Router;

/* Configure routes. */
Router::route(array(
    '/' => function() use ($db) {
            return new MessageController('Welcome', 'We have books! They are for sale! For money!');
        },
    '/books/?' => function() use ($db) {
            return new BooksController(new Books($db));
        },
    '/books/([A-Z]{4})/?' => array(function() use ($db) {
            return new BooksController(new Books($db));
        }, array('department')),
    '/books/([A-Z]{4})/([0-4][0-9]{2}[0136])/?' => array(function() use ($db) {
            return new BooksController(new Books($db));
        }, array('department', 'course')),
    '/books/([A-Z]{4})/([0-4][0-9]{2}[0136])/([A-Z][0-9])/?' => array(function() use ($db) {
            return new BooksController(new Books($db));
        }, array('department', 'course', 'section')),
    '/books/author/(.+?)/?' => array(function() use ($db) {
            return new BooksController(new Books($db));
        }, array('author')),
    '.*' => new ErrorController()
));

printf("\n<!-- Page generated in %0.6fs. -->", microtime(true) - $starttime);