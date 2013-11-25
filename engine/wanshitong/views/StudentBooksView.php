<?php namespace wanshitong\views;

use wanshitong\TemplateManager;

/**
 * A view of a list of books.
 *
 * @author kmacphail
 * @version 1.0.0
 * @since 1.0.0
 */
class StudentBooksView extends BooksView
{
    /**
     * Construct the view.
     *
     * @param array $books the books to display
     * @param string $student
     */
    public function __construct($books, $student)
    {
        parent::__construct($books, 'Books for Student ' . $student);
    }
}