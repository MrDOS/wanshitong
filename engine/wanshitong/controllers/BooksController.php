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
    private $booksRepository;

    /**
     * Construct the controller.
     *
     * @param \wanshitong\models\Books $bookRepository a book repository
     */
    public function __construct($booksRepository)
    {
        $this->booksRepository = $booksRepository;
    }

    /**
     * Display the book list.
     *
     * @param array $get GET parameters
     */
    public function get($get)
    {
        if (isset($get['author']))
            $books = $this->booksRepository->getStockedBooksByAuthor($get['author']);
        else
            $books = $this->booksRepository->getStockedBooks(
                (isset($get['department'])) ? $get['department'] : null,
                (isset($get['course'])) ? $get['course'] : null,
                (isset($get['section'])) ? $get['section'] : null
            );

        $view = new BooksView($books);
        $view->render();
    }
}