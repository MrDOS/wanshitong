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

\wanshitong\TemplateManager::setDefaultTemplateManager(new \wanshitong\TemplateManager(__DIR__ . DIRECTORY_SEPARATOR . 'templates'));

$db = new PDO('mysql:dbname=' . DB_NAME . ';host=' . DB_HOST, DB_USER, DB_PASS);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

\wanshitong\Router::route(array(
    '.*' => new \wanshitong\controllers\ErrorController()
));

printf("\n<!-- Page generated in %0.6fs. -->", microtime(true) - $starttime);