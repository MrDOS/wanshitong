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
    public function __construct($books)
    {
        $content = TemplateManager::getDefaultTemplateManager()->load('books', array('books' => $books));

        parent::__construct('Books', $content);
    }
}