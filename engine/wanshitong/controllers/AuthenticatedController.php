<?php namespace wanshitong\controllers;

use \wanshitong\controllers\Controller;
use \wanshitong\models\Staff;
use \wanshitong\views\MessageView;

/**
 * A request controller accessible only by logged-in users.
 *
 * @author scoleman
 * @version 1.0.0
 * @since 1.0.0
 */
abstract class AuthenticatedController extends Controller
{
    /**
     * Verify that the user is authenticated. If not, end the request here.
     */
    public function authenticate()
    {
        if (!Staff::isLoggedIn())
        {
            $view = new MessageView('Access Denied', 'You do not have permission to view this page.');
            $view->render();
            die();
        }
    }
}