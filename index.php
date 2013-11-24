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

require_once 'config.php';
require_once 'engine/wanshitong/Autoloader.php';
spl_autoload_register(array(new \wanshitong\Autoloader(__DIR__ . DIRECTORY_SEPARATOR . 'engine'), 'load'));

define('ROOT_URL', $_SERVER['SCRIPT_NAME']);

\wanshitong\TemplateManager::setDefaultTemplateManager(new \wanshitong\TemplateManager(__DIR__ . DIRECTORY_SEPARATOR . 'templates'));

$db = new PDO('mysql:dbname=' . DB_NAME . ';host=' . DB_HOST, DB_USER, DB_PASS);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

\wanshitong\Router::route(array(
    '/books/?' => function() use ($db) {
            return new \wanshitong\controllers\BooksController(new \wanshitong\models\Books($db));
    },
    '/books/([A-Z]{4})/?' => array(function() use ($db) {
            return new \wanshitong\controllers\BooksController(new \wanshitong\models\Books($db));
    }, array('department')),
    '/books/([A-Z]{4})/([0-4][0-9]{2}[0136])/?' => array(function() use ($db) {
            return new \wanshitong\controllers\BooksController(new \wanshitong\models\Books($db));
    }, array('department', 'course')),
    '/books/([A-Z]{4})/([0-4][0-9]{2}[0136])/([A-Z][0-9])/?' => array(function() use ($db) {
            return new \wanshitong\controllers\BooksController(new \wanshitong\models\Books($db));
    }, array('department', 'course', 'section')),
    '/books/author/(.+?)/?' => array(function() use ($db) {
            return new \wanshitong\controllers\BooksController(new \wanshitong\models\Books($db));
    }, array('author')),
    '.*' => new \wanshitong\controllers\ErrorController()
));

printf("\n<!-- Page generated in %0.6fs. -->", microtime(true) - $starttime);