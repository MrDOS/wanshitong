<?php namespace wanshitong;

/**
 * An HTML-based template.
 *
 * @author scoleman
 * @version 1.0.0
 * @since 1.0.0
 */
class Template
{
    /**
     * @var string the path to the template file
     */
    private $template_path;
    /**
     * @var array an associative array of values to be used in the template
     */
    private $values;

    /**
     * Construct the template.
     *
     * @param string $template_path the path to the template file
     * @param array $values the template values
     */
    public function __construct($template_path, $values = array())
    {
        if (!is_readable($template_path))
            throw new \Exception("Could not open template \"$template_path\"");

        $this->template_path = $template_path;
        $this->values = $values;
    }

    /**
     * Render the template to a string.
     *
     * @return string the rendered template
     */
    public function __toString()
    {
        ob_start();
        $this->render();
        return ob_get_clean();
    }

    /**
     * Replace all template values with the values given.
     *
     * @param array $values the replacement values
     */
    public function setValues($values)
    {
        $this->values = $values;
    }

    /**
     * Set a template value.
     *
     * @param string $key the key for the value
     * @param object $value the value
     */
    public function setValue($key, $value)
    {
        $this->values[$key] = $value;
    }

    /**
     * Write the rendered template to the page.
     */
    public function render()
    {
        extract($this->values);
        include $this->template_path;
    }
}