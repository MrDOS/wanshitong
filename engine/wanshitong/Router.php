<?php namespace wanshitong;

use \wanshitong\controllers\Controller;

/**
 * Request router.
 *
 * @author scoleman
 * @version 1.0.0
 * @since 1.0.0
 */
class Router
{
    /**
     * Route a user request according to given mappings. Mappings should be an
     * associative array of route rules to string names or instances of classes
     * extending \wanshitong\Controller.
     *
     * A route rule is a regex describing a URL. For example, given a site at
     * "http://example.com/", this route rule:
     *
     *     /some_request
     *
     * would match a request for "http://example.com/some_request". (The default
     * route can be described by the rule "/".)
     *
     * Parameters can be matched by regex groups:
     *
     *     /some_request/([0-9]+)
     *
     * would match a request for "http://example.com/some_request/17".
     *
     * Parameters described in such a fashion will be passed to the controller
     * as keys without values. To more sensibly retrieve the values, these
     * parameters may be named: instead of passing a single string naming the
     * controller class, pass an array with two elements, the first nameing
     * the controller, and the second being an array of parameter names as
     * strings. For example, to name the two parameters in the route:
     *
     *     /some_request/([A-Za-z]+)/([0-9]+)
     *
     * as param1 and param2 for the controller ExampleController, the controller
     * would be given as:
     *
     * array(
     *     'ExampleController',
     *     array('param1', 'param2')
     * )
     *
     * If there are more parameters matched than parameter names, or more
     * parameter names than matching parameters, the number of parameters passed
     * to the controller will be limited by the smaller number of either.
     *
     * If multiple route rules match the given URL, the first match met will be
     * used.
     *
     * In addition to any parameters described in the route, GET values from the
     * query string will be passed. Named route parameters will take precidence,
     * so be careful to avoid conflicting route parameter names and GET
     * parameter names.
     *
     * @param array $mappings request mappings
     */
    public static function route($mappings)
    {
        $path = (isset($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : '/';

        $parameters = array();
        $controller = self::getController($path, $mappings, $parameters);

        switch ($_SERVER['REQUEST_METHOD'])
        {
            case 'POST':
                $controller->post($parameters, $_POST);
                break;
            default:
                $controller->get($parameters);
                break;
        }
    }

    /**
     * Given a request URL and a set of mappings, determine the appropriate
     * controller and GET parameters.
     *
     * @param string $path the request URL
     * @param array $mappings the mappings; see {@link Router#route}
     * @param array $parameters destination array for parsed parameters
     */
    private static function getController($path, $mappings, &$parameters)
    {
        foreach ($mappings as $rule => $controller)
        {
            if (preg_match('"^' . $rule . '$"', $path, $parameters))
            {
                if (is_array($controller))
                {
                    $max_parameters = max(min(count($controller[1]), count($parameters) - 1), 0);
                    $parameters = array_combine(
                        array_slice($controller[1], 0, $max_parameters),
                        array_slice($parameters, 1, $max_parameters));
                    $controller = $controller[0];
                }
                else
                {
                    $parameters = array_slice($parameters, 1);
                }

                return ($controller instanceof Controller) ? $controller : new $controller;
            }
        }
    }
}