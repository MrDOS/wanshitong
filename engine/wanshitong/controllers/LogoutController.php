<?php namespace wanshitong\controllers;

use \wanshitong\controllers\Controller;
use \wanshitong\models\Staff;

/**
 * Handle user logouts.
 *
 * @author scoleman
 * @version 1.0.0
 * @since 1.0.0
 */
class LogoutController extends Controller
{
    /**
     * Log the user out.
     *
     * @param array $get GET parameters
     */
    public function get($get)
    {
        Staff::logOut();
        header('Location: ' . ROOT_URL);
    }
}