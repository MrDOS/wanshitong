<?php namespace wanshitong\controllers;

use \wanshitong\views\OrderView;

/**
 * Display an order.
 *
 * @author scoleman
 * @version 1.0.0
 * @since 1.0.0
 */
class OrderController extends AuthenticatedController
{
    private $orders_repository;
    private $students_repository;
    private $books_repository;

    /**
     * Construct the controller.
     *
     * @param \wanshitong\models\Orders $orders_repository the orders repository
     */
    public function __construct($orders_repository, $students_repository, $books_repository)
    {
        $this->orders_repository = $orders_repository;
        $this->students_repository = $students_repository;
        $this->books_repository = $books_repository;
    }

    /**
     * Handle a GET request.
     *
     * @param array $get GET parameters
     */
    public function get($get)
    {
        parent::authenticate();

        $order = $this->orders_repository->getOrderByID($get['order']);
        $student = $this->students_repository->getStudentByNumber($order->student_number);
        $books = $this->books_repository->getBooksByOrder($order->order_id);

        $view = new OrderView($order, $student, $books);
        return $view->render();
    }
}