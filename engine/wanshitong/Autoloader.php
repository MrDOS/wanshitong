<?php namespace wanshitong;

/**
 * Namespace separator token.
 */
define('NAMESPACE_SEPARATOR', '\\');

/**
 * Class autoloader.
 *
 * @author scoleman
 * @version 1.0.0
 * @since 1.0.0
 */
class Autoloader
{
    /**
     * @var string the directory in which classes are located
     */
    private $base_dir;

    /**
     * Construct the autoloader with a given base directory.
     *
     * @param string $base_dir the directory in which classes are located
     * @throws \Exception if the base directory is inaccessible
     */
    public function __construct($base_dir)
    {
        if (is_readable($base_dir) && is_dir($base_dir))
            $this->base_dir = $base_dir;
        else
            throw new \Exception("Base class directory is unreadable.");
    }

    /**
     * Load a class by name.
     *
     * @param string $class_name the fully-qualified class name
     */
    public function load($class_name)
    {
        $class_file = $this->base_dir . DIRECTORY_SEPARATOR . str_replace(NAMESPACE_SEPARATOR, DIRECTORY_SEPARATOR, $class_name) . '.php';
        if (is_readable($class_file))
            include $class_file;
    }
}