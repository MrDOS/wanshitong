<?php namespace wanshitong\views;

use wanshitong\TemplateManager;

/**
 * A view of a list of books.
 *
 * @author kmacphail
 * @version 1.0.0
 * @since 1.0.0
 */
class FilterableBooksView extends BooksView
{
    /**
     * Construct the view.
     *
     * @param array $books the books to display
     * @param array $departments all departments
     * @param array $department the currently selected department; may be null
     * @param array $courses all courses
     * @param array $course the currently selected course; may be null
     * @param array $sections all sections
     * @param array $section the currently selected section; may be null
     * @param array $authors all authors
     * @param array $author the currently selected author; may be null
     */
    public function __construct($books, $departments, $department, $courses, $course, $sections, $section, $authors, $author)
    {
        $navigation = TemplateManager::getDefaultTemplateManager()->load('book_menu', array(
                'departments' => $departments,
                'selected_department' => $department,
                'courses' => $courses,
                'selected_course' => $course,
                'sections' => $sections,
                'selected_section' => $section,
                'authors' => $authors,
                'selected_author' => $author));

        parent::__construct($books);
        parent::setBookNavigation($navigation);
    }
}