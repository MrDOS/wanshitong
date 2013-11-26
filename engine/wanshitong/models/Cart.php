<?php namespace wanshitong\models;

/**
 * Manage the contents of the cart.
 *
 * @author scoleman
 * @version 1.0.0
 * @since 1.0.0
 */
class Cart
{
    /**
     * Get the number of items in the cart.
     *
     * @return int the number of items in the cart
     */
    public static function size()
    {
        return (isset($_SESSION['cart_books'])) ? count($_SESSION['cart_books']) : 0;
    }

    /**
     * Remove all books from the class.
     */
    public static function emptyCart()
    {
        $_SESSION['cart_books'] = array();
    }

    /**
     * Add a book to the cart.
     *
     * @param stdClass the book
     */
    public static function addBook($book)
    {
        if (!isset($_SESSION['cart_books']))
            $_SESSION['cart_books'] = array();
        $book->quantity = 1;

        /* Make sure the book doesn't already exist in the cart. */
        foreach ($_SESSION['cart_books'] as $other_book)
            if ($other_book->isbn == $book->isbn)
                /* If it does, we'll just increment the quantity of the existing
                 * record and bail out. */
                return $other_book->quantity++;

        $_SESSION['cart_books'][] = $book;
    }

    /**
     * Update the quantities of cart books. Quantities should be ordered
     * similarly to the array returned by {@link Cart#getBooks}. If any quantity
     * is 0, its associated book will be removed from the cart.
     *
     * @param int[] $quantities the new quantities
     */
    public static function updateQuantities($quantities)
    {
        $i = 0;
        foreach ($_SESSION['cart_books'] as $index => $book)
        {
            $book->quantity = $quantities[$i++];
            if ($book->quantity <= 0)
                unset($_SESSION['cart_books'][$index]);
        }
    }

    /**
     * Retrieve books from the cart.
     *
     * @param stdClass[] books in the cart
     */
    public static function getBooks()
    {
        return (isset($_SESSION['cart_books'])) ? $_SESSION['cart_books'] : array();
    }
}