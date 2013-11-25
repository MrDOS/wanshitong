<?php namespace wanshitong\controllers;

use \wanshitong\controllers\Controller;
use \wanshitong\models\Cart;
use \wanshitong\views\CartView;
use \wanshitong\views\MessageView;

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
     * @var \wanshitong\models\Students
     */
    private $students_repository;

    /**
     * Construct the repository.
     *
     * @param \wanshitong\models\Books a books repository
     * @param \wanshitong\models\Orders an orders repository
     * @param \wanshitong\models\Students a students repository
     */
    public function __construct($books_repository, $orders_repository, $students_repository)
    {
        $this->books_repository = $books_repository;
        $this->orders_repository = $orders_repository;
        $this->students_repository = $students_repository;
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
        elseif (isset($post['order']))
        {
            if (!isset($post['student_number']) || $this->students_repository->getStudentByNumber($post['student_number']) === false)
            {
                $view = new MessageView('Invalid student details', 'Please provide a valid student number.');
                return $view->render();
            }

            $order_id = $this->orders_repository->insertOrder($post['student_number'], Cart::getBooks());
            Cart::emptyCart();

            $view = new MessageView('Order submitted', 'Your order number is <strong>#' . $order_id . '</strong>. Please make a note of this; it will be required to pick up your order.');
            return $view->render();
        }

        return $this->get($get);
    }
}