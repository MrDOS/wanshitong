<?php namespace wanshitong\views;

use \wanshitong\views\BooksView;
use \wanshitong\TemplateManager;

/**
 * An administrative view of an order.
 *
 * @author kmacphail
 * @version 1.0.0
 * @since 1.0.0
 */
class OrderView implements View
{
    private $order;
    private $student;
    private $books;

    /**
     * Construct the view.
     *
     * @param stdClass $order the order
     * @param stdClass $student the student who placed the order
     * @param stdClass[] $books the books in the order
     */
    public function __construct($order, $student, $books)
    {
        $this->order = $order;
        $this->student = $student;
        $this->books = $books;
    }

    public function render()
    {
        $content = TemplateManager::getDefaultTemplateManager()->load('order', array(
            'order' => $this->order,
            'student' => $this->student,
            'books' => TemplateManager::getDefaultTemplateManager()->load('books', array(
                'books' => $this->books,
                'header' => null,
                'submit_name' => null,
                'submit_label' => null,
                'can_order' => false,
                'show_quantity' => true
            ))));
        $content->render();
    }
}