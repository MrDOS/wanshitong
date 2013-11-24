<?php namespace wanshitong\controllers;

use wanshitong\controllers\Controller;
use wanshitong\views\BooksView;
use wanshitong\models\Books;

/**
 * A request for a book list.
 *
 * @author scoleman
 * @version 1.0.0
 * @since 1.0.0
 */
class BooksController extends Controller
{
    private $books_repository;

    /**
     * Construct the controller.
     *
     * @param \wanshitong\models\Books $bookRepository a book repository
     */
    public function __construct($books_repository)
    {
        $this->books_repository = $books_repository;
    }

    /**
     * Display the book list.
     *
     * @param array $get GET parameters
     */
    public function get($get)
    {
        if (isset($get['author']))
            $books = $this->books_repository->getStockedBooksByAuthor($get['author']);
        else
            $books = $this->books_repository->getStockedBooks(
                (isset($get['department'])) ? $get['department'] : null,
                (isset($get['course'])) ? $get['course'] : null,
                (isset($get['section'])) ? $get['section'] : null
            );

        $view = new BooksView($books);
        $view->render();
    }
}