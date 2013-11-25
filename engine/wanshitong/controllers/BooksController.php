<?php namespace wanshitong\controllers;

use \wanshitong\controllers\Controller;
use \wanshitong\models\Books;
use \wanshitong\views\BooksView;

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
        $department = (isset($get['department']) && !empty($get['department'])) ? preg_replace('/[^A-Za-z0-9\-., ]/', '', $get['department']) : null;
        $course = (isset($get['course']) && !empty($get['course'])) ? preg_replace('/[^A-Za-z0-9\-., ]/', '', $get['course']) : null;
        $section = (isset($get['section']) && !empty($get['section'])) ? preg_replace('/[^A-Za-z0-9\-., ]/', '', $get['section']) : null;
        $author = (isset($get['author']) && !empty($get['author'])) ? preg_replace('/[^A-Za-z0-9\-., ]/', '', $get['author']) : null;

        if ($author !== null)
            $books = $this->books_repository->getStockedBooksByAuthor($author);
        else
            $books = $this->books_repository->getStockedBooks($department, $course, $section);

        $departments = $this->departments_repository->getDepartments();
        $courses = array();
        $sections = array();
        if ($department !== null)
            $courses = $this->departments_repository->getCoursesByDepartment($department);
        if ($department !== null && $course !== null)
            $sections = $this->departments_repository->getSectionsByCourse($department, $course);

        $authors = $this->authors_repository->getAuthors();

        $view = new BooksView($books, $departments, $department, $courses, $course, $sections, $section, $authors, $author);
        $view->render();
    }
}