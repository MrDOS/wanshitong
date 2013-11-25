<?php namespace wanshitong\controllers;

use \wanshitong\controllers\Controller;
use \wanshitong\models\Cart;
use \wanshitong\views\CartView;

class CartController extends Controller
{
    /**
     * @var \wanshitong\models\Books
     */
    private $books_repository;

    /**
     * @var \wanshitong\models\Orders
     */
    private $orders_repository;

    /**
     * Construct the repository.
     *
     * @param \wanshitong\models\Orders an orders repository
     */
    public function __construct($books_repository, $orders_repository)
    {
        $this->books_repository = $books_repository;
        $this->orders_repository = $orders_repository;
    }

    /**
     * Display the cart.
     *
     * @param array $get GET parameters
     */
    public function get($get)
    {
        $books = Cart::getBooks();
        $view = new CartView($books);
        $view->render();
    }

    /**
     * Handle actions against the cart.
     *
     * @param array $get GET parameters
     * @param array $post POSTed values
     */
    public function post($get, $post)
    {
        if (isset($post['cart_empty']))
        {
            Cart::emptyCart();
        }
        elseif (isset($post['cart_add']))
        {
            foreach ($post['books'] as $isbn)
                if (($book = $this->books_repository->getBookByISBN($isbn)) !== false)
                    Cart::addBook($book);
        }
        elseif (isset($post['cart_quantities']))
        {
            Cart::updateQuantities($post['book_quantities']);
        }

        return $this->get($get);
    }
}