<?php namespace wanshitong\views;

use wanshitong\TemplateManager;

/**
 * A view of a list of books.
 *
 * @author kmacphail
 * @version 1.0.0
 * @since 1.0.0
 */
class BooksView extends PageView
{
    /**
     * @var \wanshitong\Template
     */
    private $content;

    /**
     * Construct the view.
     *
     * @param array $books the books to display
     */
    public function __construct($books, $header = null, $can_order = true, $submit_name = 'cart_add', $submit_label = 'Add to Cart', $show_quantity = false)
    {
        $this->content = TemplateManager::getDefaultTemplateManager()->load('books', array(
                'books' => $books,
                'header' => $header,
                'can_order' => $can_order,
                'submit_name' => $submit_name,
                'submit_label' => $submit_label,
                'show_quantity' => $show_quantity));
        parent::__construct('Books', $this->content);
    }

    public function setHeader($header)
    {
        $this->content->setValue('header', $header);
    }

    public function setCanOrder($can_order)
    {
        $this->content->setValue('can_order', $can_order);
    }

    public function setSubmitName($submit_name)
    {
        $this->content->setValue('submit_name', $submit_name);
    }

    public function setSubmitLabel($submit_label)
    {
        $this->content->setValue('submit_label', $submit_label);
    }

    public function setShowQuantity($show_quantity)
    {
        $this->content->setValue('show_quantity', $show_quantity);
    }
}