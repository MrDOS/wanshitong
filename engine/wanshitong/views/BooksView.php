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
     * Construct the view.
     *
     * @param array $books the books to display
     */
    public function __construct($books, $header = null, $submit_name = 'cart_add', $submit_label = 'Add to Cart')
    {
        $content = TemplateManager::getDefaultTemplateManager()->load('books', array(
                'books' => $books,
                'header' => $header,
                'submit_name' => $submit_name,
                'submit_label' => $submit_label));
        parent::__construct('Books', $content);
    }
}