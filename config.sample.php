<?php

define('DB_HOST', 'localhost');
define('DB_NAME', '');
define('DB_USER', '');
define('DB_PASS', '');

define('SITE_NAME', 'Bookstore');
define('SITE_OWNER', 'bookstore owner');

define('ROOT_URL', $_SERVER['SCRIPT_NAME']);
define('RES_URL', substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], '/')) . '/static');