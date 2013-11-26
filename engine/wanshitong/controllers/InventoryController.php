<?php namespace wanshitong\controllers;

use \wanshitong\controllers\Controller;
use \wanshitong\models\Authors;
use \wanshitong\models\Books;
use \wanshitong\models\Departments;
use \wanshitong\views\FilterableBooksView;

/**
 * A request for a book list. The same as {@link BooksController} with the
 * exception that it lists unstocked books.
 *
 * @author scoleman
 * @version 1.0.0
 * @since 1.0.0
 */
class InventoryController extends BooksController
{
    /**
     * Construct the controller.
     *
     * @param \wanshitong\models\Books $books_epository
     * @param \wanshitong\models\Departments $departments_repository
     * @param \wanshitong\models\Authors $authors_repository
     */
    public function __construct(Books $books_repository, Departments $departments_repository, Authors $authors_repository)
    {
        $books_repository->setShowUnstocked(true);
        parent::__construct($books_repository, $departments_repository, $authors_repository);
    }
}