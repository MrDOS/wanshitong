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
     * associative array of route rules to classes extending
     * {@link \wanshitong\Controller}.
     *
     * A route rule is a regex describing a URL. For example, given a site at
     * "http://example.com/", this route rule:
     *
     * <pre>
     *     /some_request
     * </pre>
     *
     * would match a request for <copde>http://example.com/some_request</code>.
     * (The default route can be described by the rule <code>/</code>.)
     *
     * Parameters can be matched by regex groups:
     *
     * <pre>
     *     /some_request/([0-9]+)
     * </pre>
     *
     * would match the request <code>http://example.com/some_request/17</code>.
     *
     * Parameters described in such a fashion will be passed to the controller
     * as keys without values. To more sensibly retrieve the values, these
     * parameters may be named: instead of passing a single string naming the
     * controller class, pass an array with two elements, the first nameing
     * the controller, and the second being an array of parameter names as
     * strings. For example, to name the two parameters in the route:
     *
     * <pre>
     *     /some_request/([A-Za-z]+)/([0-9]+)
     * </pre>
     *
     * as <code>param1</code> and <code>param2</code> for the controller
     * <code>ExampleController</code>, the controller would be given as:
     *
     * <pre>
     *     array(
     *         'ExampleController',
     *         array('param1', 'param2')
     *     )
     * </pre>
     *
     * If there are more parameters matched than parameter names, or more
     * parameter names than matching parameters, the number of parameters passed
     * to the controller will be limited by the smaller number of either.
     *
     * If multiple route rules match the given URL, the first match met will be
     * used.
     *
     * The controller itself may be passed in one of three ways: it may be the
     * string name of a Controller, an instance of a Controller, or a callable
     * that returns an instance of a Contoller. This third method is useful when
     * the construction of the controller or its dependencies is expensive. Note
     * that the callable itself is <em>not</em> used as the route destination,
     * but is simply used to provide the route destination.
     *
     * In addition to any parameters described in the route, GET values from the
     * query string will be passed. Named route parameters will take precidence,
     * so be careful to avoid conflicting route parameter names and GET
     * parameter names.
     *
     * @param array $mappings request mappings
     * @return mixed anything returned by the controller; usually null
     */
    public static function route($mappings)
    {
        $path = (isset($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : '/';

        $parameters = array();
        $controller = self::getController($path, $mappings, $parameters);

        switch ($_SERVER['REQUEST_METHOD'])
        {
            case 'POST':
                return $controller->post($parameters, $_POST);
            default:
                return $controller->get($parameters);
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

                $parameters = array_merge($_GET, $parameters);
                if ($controller instanceof Controller)
                    return $controller;
                elseif (is_callable($controller))
                    return $controller();
                return new $controller;
            }
        }
    }
}