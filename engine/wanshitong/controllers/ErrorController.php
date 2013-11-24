<?php namespace wanshitong\controllers;

use \wanshitong\controllers\Controller;

/**
 * A generic error controller.
 *
 * @author scoleman
 * @version 1.0.0
 * @since 1.0.0
 */
class ErrorController extends Controller
{
    public function get($get)
    {
        \wanshitong\TemplateManager::getDefaultTemplateManager()->load('page', array(
                'page_title' => 'Page Not Found',
                'content' => '<p>The resource you requested could not be located.</p>'
            ))->render();
    }
}