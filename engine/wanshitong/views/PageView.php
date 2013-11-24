<?php namespace wanshitong\views;

use \wanshitong\views\View;
use \wanshitong\TemplateManager;

/**
 * A view which uses the standard page template.
 */
class PageView implements View
{
    private $title;
    private $content;

    /**
     * Construct the view.
     *
     * @param string $title the page title
     * @param string|\wanshitong\Template $content the page content
     */
    public function __construct($title, $content)
    {
        $this->title = $title;
        $this->content = $content;
    }

    public function render()
    {
        $page = TemplateManager::getDefaultTemplateManager()->load('page', array(
                'page_title' => $this->title,
                'cart_size' => 0,
                'staff_navigation' => '',
                'book_navigation' => '',
                'content' => $this->content
            ));
        $page->render();
    }
}