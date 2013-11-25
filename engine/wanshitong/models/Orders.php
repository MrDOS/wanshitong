<?php namespace wanshitong\models;

use \wanshitong\models\Repository;

/**
 * Orders repository.
 *
 * @author nwetmore
 * @version 1.0.0
 * @since 1.0.0
 */
class Orders implements Repository
{
    /**
     * @var \PDO
     */
    private $db;

    /**
     * Construct the repository.
     *
     * @param \PDO $db the database
     */
    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Insert an order.
     *
     * @param string $student_number the student number associated with the order
     * @param stdClass[] $books the books being ordered
     */
    public function insertOrder($student_number, $books)
    {
        if (count($books) == 0)
            throw new \Exception('Orders must contain at least one book');

        $quote = $this->db->prepare(<<<SQL
INSERT INTO orders
(orders.order_date, orders.arrival_date, orders.student_number)
VALUES (NOW(), NULL, :student_number);
SQL
        );
        $quote->execute(array(':student_number' => $student_number));
        if ($quote === false)
            throw new \Exception('Could not store order');
        $order_id = $this->db->lastInsertId();

        foreach ($books as $book)
        {
            $book_query = $this->db->prepare(<<<SQL
INSERT INTO order_books
(order_books.order_id, order_books.isbn, order_books.quantity)
VALUES (:order_id, :isbn, :quantity);
SQL
            );
            $book_query->execute(array(':order_id' => $order_id, ':isbn' => $book->isbn, ':quantity' => $book->quantity));
        }

        return $order_id;
    }
}