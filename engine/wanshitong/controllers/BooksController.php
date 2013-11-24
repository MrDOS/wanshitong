<?php namespace wanshitong\controllers;

use wanshitong\controllers\Controller;
use wanshitong\models\Books;
use wanshitong\TemplateManager;

class BooksController extends Controller
{
    private $booksRepository;

    public function __construct($booksRepository)
    {
        $this->booksRepository = $booksRepository;
    }

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

        TemplateManager::getDefaultTemplateManager()->load('books', array('books' => $books))->render();
    }
}