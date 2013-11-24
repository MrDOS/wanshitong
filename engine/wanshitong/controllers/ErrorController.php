<?php namespace wanshitong\controllers;

use \wanshitong\controllers\Controller;
use wanshitong\views\MessageView;

/**
 * A generic error controller.
 *
 * @author scoleman
 * @version 1.0.0
 * @since 1.0.0
 */
class ErrorController extends Controller
{
    /**
     * Display the error.
     *
     * @param array $get GET parameters
     */
    public function get($get)
    {
        $view = new MessageView('Page Not Found', 'The resource you requested could not be located.');
        $view->render();
    }
}