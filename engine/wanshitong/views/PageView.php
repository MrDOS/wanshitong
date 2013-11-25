<?php namespace wanshitong\views;

use \wanshitong\models\Staff;
use \wanshitong\views\View;
use \wanshitong\TemplateManager;

/**
 * A view which uses the standard page template.
 */
class PageView implements View
{
    private $title;
    private $content;
    private $book_navigation;

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

    /**
     * Set the book navigation bar.
     *
     * @param string|\wanshitong\Template $book_navigation the navigation bar
     */
    public function setBookNavigation($book_navigation)
    {
        $this->book_navigation = $book_navigation;
    }

    public function render()
    {
        if ($this->book_navigation == null)
            $this->book_navigation = '<center><a href="' . ROOT_URL . '/books">Search for Books?</a></center>';

        if (!Staff::isLoggedIn())
            $staff_navigation = TemplateManager::getDefaultTemplateManager()->load('login');
        else
            $staff_navigation = TemplateManager::getDefaultTemplateManager()->load('staff_menu');

        $page = TemplateManager::getDefaultTemplateManager()->load('page', array(
                'page_title' => $this->title,
                'cart_size' => 0,
                'staff_navigation' => $staff_navigation,
                'book_navigation' => $this->book_navigation,
                'content' => $this->content
            ));
        $page->render();
    }
}