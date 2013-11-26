<?php namespace wanshitong\controllers;

use \wanshitong\controllers\Controller;
use \wanshitong\models\Authors;
use \wanshitong\models\Books;
use \wanshitong\models\Departments;
use \wanshitong\views\FilterableBooksView;

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

    private $show_quantity;
    private $can_order;

    /**
     * Construct the controller.
     *
     * @param \wanshitong\models\Books $books_epository
     * @param \wanshitong\models\Departments $departments_repository
     * @param \wanshitong\models\Authors $authors_repository
     */
    public function __construct(Books $books_repository, Departments $departments_repository, Authors $authors_repository)
    {
        $this->books_repository = $books_repository;
        $this->departments_repository = $departments_repository;
        $this->authors_repository = $authors_repository;

        $this->show_quantity = false;
        $this->can_order = true;
    }

    public function setShowQuantity($show_quantity)
    {
        $this->show_quantity = $show_quantity;
    }

    public function setCanOrder($can_order)
    {
        $this->can_order = $can_order;
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
            $books = $this->books_repository->getBooksByAuthor($author);
        else
            $books = $this->books_repository->getBooks($department, $course, $section);

        $departments = $this->departments_repository->getDepartments();
        $courses = array();
        $sections = array();
        if ($department !== null)
            $courses = $this->departments_repository->getCoursesByDepartment($department);
        if ($department !== null && $course !== null)
            $sections = $this->departments_repository->getSectionsByCourse($department, $course);

        $authors = $this->authors_repository->getAuthors();

        $view = new FilterableBooksView($books, $departments, $department, $courses, $course, $sections, $section, $authors, $author);
        $view->setShowQuantity($this->show_quantity);
        $view->setCanOrder($this->can_order);
        $view->render();
    }
}