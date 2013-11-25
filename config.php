<?php

define('DB_HOST', 'localhost');
define('DB_NAME', 'wanshitong');
define('DB_USER', 'wanshitong');
define('DB_PASS', 'asdf');

define('SITE_NAME', 'Bookstore');
define('SITE_OWNER', 'bookstore owner');

define('ROOT_URL', $_SERVER['SCRIPT_NAME']);
define('RES_URL', substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], '/')) . '/static');