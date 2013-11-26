<?php namespace wanshitong\controllers;

use \wanshitong\controllers\AuthenticatedController;
use \wanshitong\models\Authors;
use \wanshitong\models\Books;
use \wanshitong\models\Departments;
use \wanshitong\views\ManagementView;

/**
 * Manage books, authors, mappings between them, and mappings between books and
 * courses.
 *
 * @author scoleman
 * @version 1.0.0
 * @since 1.0.0
 */
class ManagementController extends Controller
{
    private $books_repository;
    private $departments_repository;
    private $authors_repository;

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
    }

    /**
     * Display the management interface.
     *
     * @param array $get GET parameters
     */
    public function get($get)
    {
        $authors = $this->authors_repository->getAuthors();
        $sections = $this->departments_repository->getCurrentSections();

        $view = new ManagementView($authors, $sections);
        return $view->render();
    }

    /**
     * Effect changes.
     *
     * @param array $get GET parameters
     * @param array $post POSTed values
     */
    public function post($get, $post)
    {
        if (isset($post['manage_add']))
        {
            $isbn = preg_replace('/[^0-9]/', '', $post['isbn']);
            $title = preg_replace('/[^A-Za-z0-9:;\-., ]/', '', $post['title']);
            $price = preg_replace('/[^0-9]/', '', $post['price']);

            $this->books_repository->insertBook($isbn, $title, 0, $price);
        }
        elseif (isset($post['manage_stocked']))
        {
            $isbn = preg_replace('/[^0-9]/', '', $post['isbn']);

            $this->books_repository->setStocked($isbn, ($post['stocked'] === 'true') ? 1 : 0);
        }
        elseif (isset($post['manage_author_add']))
        {
            $given_name = preg_replace('/[^A-Za-z0-9\-. ]/', '', $post['given_name']);
            $family_name = preg_replace('/[^A-Za-z0-9\-. ]/', '', $post['family_name']);

            $this->authors_repository->insertAuthor($given_name, $family_name);
        }
        elseif (isset($post['manage_author_remove']))
        {
            $author_id = preg_replace('/[^0-9]/', '', $post['author_id']);

            $this->authors_repository->deleteAuthor($author_id);
        }
        elseif (isset($post['manage_book_author_add']))
        {
            $isbn = preg_replace('/[^0-9]/', '', $post['isbn']);
            $author_id = preg_replace('/[^0-9]/', '', $post['author_id']);

            $this->authors_repository->associateAuthorWithBook($author_id, $isbn);
        }
        elseif (isset($post['manage_book_author_remove']))
        {
            $isbn = preg_replace('/[^0-9]/', '', $post['isbn']);
            $author_id = preg_replace('/[^0-9]/', '', $post['author_id']);

            $this->authors_repository->disassociateAuthorWithBook($author_id, $isbn);
        }
        elseif (isset($post['manage_book_author_add']))
        {
            $isbn = preg_replace('/[^0-9]/', '', $post['isbn']);
            $author_id = preg_replace('/[^0-9]/', '', $post['author_id']);

            $this->authors_repository->associateAuthorWithBook($author_id, $isbn);
        }
        elseif (isset($post['manage_book_author_remove']))
        {
            $isbn = preg_replace('/[^0-9]/', '', $post['isbn']);
            $author_id = preg_replace('/[^0-9]/', '', $post['author_id']);

            $this->authors_repository->disassociateAuthorWithBook($author_id, $isbn);
        }
        elseif (isset($post['manage_book_section_add']))
        {
            $isbn = preg_replace('/[^0-9]/', '', $post['isbn']);
            $section_number = preg_replace('/[^0-9]/', '', $post['section_number']);

            $this->departments_repository->associateSectionWithBook($section_number, $isbn);
        }
        elseif (isset($post['manage_book_section_remove']))
        {
            $isbn = preg_replace('/[^0-9]/', '', $post['isbn']);
            $section_number = preg_replace('/[^0-9]/', '', $post['section_number']);

            $this->departments_repository->disassociateSectionWithBook($section_number, $isbn);
        }

        $this->get($get);
    }
}