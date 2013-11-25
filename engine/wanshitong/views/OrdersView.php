<?php namespace wanshitong\views;

use \wanshitong\TemplateManager;

/**
 * An administrative view of orders.
 *
 * @author kmacphail
 * @version 1.0.0
 * @since 1.0.0
 */
class OrdersView extends PageView
{
    /**
     * Construct the view.
     *
     * @param stdClass[] $orders outstanding orders
     * @param stdClass[] $fulfilled_orders fulfilled orders
     */
    public function __construct($orders, $fulfilled_orders)
    {
        $content = TemplateManager::getDefaultTemplateManager()->load('orders', array(
            'orders' => $orders,
            'fulfilled_orders' => $fulfilled_orders));
        parent::__construct('Orders', $content);
    }
}