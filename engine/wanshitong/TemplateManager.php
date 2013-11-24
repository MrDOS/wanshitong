<?php namespace wanshitong;

/**
 * Manage loading of templates.
 *
 * @author scoleman
 * @version 1.0.0
 * @since 1.0.0
 */
class TemplateManager
{
    /**
     * @var string the directory in which templates are located
     */
    private $base_dir;

    /**
     * @var \wanshitong\TemplateManager the global default template manager
     */
    private static $default_template_manager;

    /**
     * Construct the template manager with a given base directory.
     *
     * @param string $base_dir the directory in which templates are located
     * @throws \Exception if the base directory is inaccessible
     */
    public function __construct($base_dir)
    {
        if (is_readable($base_dir) && is_dir($base_dir))
            $this->base_dir = $base_dir;
        else
            throw new \Exception("Base template directory is unreadable.");
    }

    /**
     * Get the global default template manager.
     *
     * @return \wanshitong\TemplateManager the global default template manager
     */
    public static function getDefaultTemplateManager()
    {
        if (self::$default_template_manager === null)
            self::$default_template_manager = new TemplateManager(__DIR__);
        return self::$default_template_manager;
    }

    /**
     * Set the global default template manager.
     *
     * @param \wanshitong\TemplateManager $template_manager
     *               the global default template manager
     */
    public static function setDefaultTemplateManager(TemplateManager $template_manager)
    {
        self::$default_template_manager = $template_manager;
    }

    /**
     * Load a template by name.
     *
     * @param string $class_name the fully-qualified class name
     * @param array $values the template values
     * @throws \Exception if the template is inaccessible
     */
    public function load($template_name, $values = array())
    {
        $template_file = $this->base_dir . DIRECTORY_SEPARATOR . $template_name . '.phtml';
        if (is_readable($template_file))
            return new \wanshitong\Template($template_file, $values);
        else
            throw new \Exception("Template does not exist or is inaccessible");
    }
}