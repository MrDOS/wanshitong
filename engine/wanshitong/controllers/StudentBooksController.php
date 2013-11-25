<?php namespace wanshitong\controllers;

use \wanshitong\controllers\Controller;
use \wanshitong\models\Books;
use \wanshitong\views\MessageView;
use \wanshitong\views\StudentBooksView;
use \wanshitong\TemplateManager;

/**
 * A request for a student's book list.
 *
 * @author scoleman
 * @version 1.0.0
 * @since 1.0.0
 */
class StudentBooksController extends Controller
{
    private $books_repository;
    private $departments_repository;
    private $authors_repository;

    /**
     * Construct the controller.
     *
     * @param \wanshitong\models\Books $bookRepository a book repository
     */
    public function __construct($books_repository, $departments_repository, $authors_repository)
    {
        $this->books_repository = $books_repository;
        $this->departments_repository = $departments_repository;
        $this->authors_repository = $authors_repository;
    }

    /**
     * Display the book list.
     *
     * @param array $get GET parameters
     */
    public function get($get)
    {
        $student = (isset($get['student'])) ? preg_replace('/[^A-Za-z0-9\-., ]/', '', $get['student']) : '';

        if (!$student)
        {
            $view = new MessageView('Enter your student number to retrieve textbooks',
                TemplateManager::getDefaultTemplateManager()->load('student_selection'));
            return $view->render();
        }

        $books = $this->books_repository->getBooksByStudent($student);
        if ($books === false || count($books) == 0)
            $view = new MessageView('No books for student', 'No books were found for student ' . $student . '.');
        else
            $view = new StudentBooksView($books, $student);
        $view->render();
    }
}