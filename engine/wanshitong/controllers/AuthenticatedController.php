<?php namespace wanshitong\controllers;

use \wanshitong\controllers\Controller;
use \wanshitong\models\Staff;
use \wanshitong\views\MessageView;

/**
 * A sort-of controller that lets other controllers authenticate the user.
 *
 * @author scoleman
 * @version 1.0.0
 * @since 1.0.0
 */
class AuthenticatedController
{
    /**
     * Verify that the user is authenticated. If not, end the request here.
     */
    public static function authenticate()
    {
        if (!Staff::isLoggedIn())
        {
            $view = new MessageView('Access Denied', 'You do not have permission to view this page.');
            $view->render();
            die();
        }
    }
}