<?php namespace wanshitong\controllers;

/**
 * A request controller.
 *
 * @author scoleman
 * @version 1.0.0
 * @since 1.0.0
 */
abstract class Controller
{
    /**
     * Handle a GET request.
     *
     * @param array $get GET parameters as an associative array
     * @return \wanshitong\Template a template representative of the page content
     */
    public abstract function get($get);

    /**
     * Handle a POST request.
     *
     * @param array $get GET parameters as an associative array
     * @param array $post POSTed values as an associative array
     */
    public function post($get, $post)
    {
        return $this->get($get);
    }
}