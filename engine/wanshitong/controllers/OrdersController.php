<?php namespace wanshitong\controllers;

use \wanshitong\controllers\AuthenticatedController;
use \wanshitong\views\OrdersView;

/**
 * Orders list and management.
 *
 * @author scoleman
 * @version 1.0.0
 * @since 1.0.0
 */
class OrdersController extends Controller
{
    private $orders_repository;

    /**
     * Construct the controller.
     *
     * @param \wanshitong\models\Orders $orders_repository the orders repository
     */
    public function __construct($orders_repository)
    {
        $this->orders_repository = $orders_repository;
    }

    /**
     * Handle a GET request.
     *
     * @param array $get GET parameters
     */
    public function get($get)
    {
        AuthenticatedController::authenticate();

        $orders = $this->orders_repository->getOutstandingOrders();
        $fulfilled_orders = $this->orders_repository->getFulfilledOrders();

        $view = new OrdersView($orders, $fulfilled_orders);
        return $view->render();
    }

    /**
     * Handle a POST request to fulfill orders.
     *
     * @param array $get GET parameters
     * @param array $post POSTed values
     */
    public function post($get, $post)
    {
        AuthenticatedController::authenticate();

        if (isset($post['order_fulfill']))
        {
            $this->orders_repository->fulfillOrder($post['order_id']);
        }

        $this->get($get);
    }
}