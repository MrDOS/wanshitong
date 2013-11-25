<?php namespace wanshitong\views;

use wanshitong\TemplateManager;

/**
 * A view of books in the cart.
 *
 * @author kmacphail
 * @version 1.0.0
 * @since 1.0.0
 */
class CartView extends PageView
{
    /**
     * Construct the view.
     *
     * @param array $books the books to display
     */
    public function __construct($books)
    {
        $content = TemplateManager::getDefaultTemplateManager()->load('cart', array(
                'books' => $books));
        parent::__construct('Cart', $content);
    }
}