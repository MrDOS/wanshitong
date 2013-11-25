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
     * Get all outstanding orders.
     *
     * @return stdClass[] all outstanding orders
     */
    public function getOutstandingOrders()
    {
        $order = $this->db->prepare(<<<SQL
SELECT orders.order_id, orders.order_date, orders.arrival_date, orders.student_number
FROM orders
WHERE orders.arrival_date IS NULL
ORDER BY orders.order_date;
SQL
        );
        $order->execute();
        return $order->fetchAll(\PDO::FETCH_OBJ);
    }

    /**
     * Get all outstanding orders.
     *
     * @return stdClass[] all outstanding orders
     */
    public function getFulfilledOrders()
    {
        $order = $this->db->prepare(<<<SQL
SELECT orders.order_id, orders.order_date, orders.arrival_date, orders.student_number
FROM orders
WHERE orders.arrival_date IS NOT NULL
ORDER BY orders.order_date;
SQL
        );
        $order->execute();
        return $order->fetchAll(\PDO::FETCH_OBJ);
    }

    /**
     * Get an order by its ID.
     *
     * @return stdClass the order
     */
    public function getOrderByID($order_id)
    {
        $order = $this->db->prepare(<<<SQL
SELECT orders.order_id, orders.order_date, orders.arrival_date, orders.student_number
FROM orders
WHERE orders.order_id = :order_id
ORDER BY orders.order_date;
SQL
        );
        $order->execute(array(':order_id' => $order_id));
        return $order->fetch(\PDO::FETCH_OBJ);
    }

    /**
     * Fulfill an order by its ID.
     *
     * @param int $order_id the order ID
     */
    public function fulfillOrder($order_id)
    {
        $order = $this->db->prepare(<<<SQL
UPDATE orders SET arrival_date = NOW() WHERE orders.order_id = :order_id;
SQL
        );
        $order->execute(array(':order_id' => $order_id));
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

        $order = $this->db->prepare(<<<SQL
INSERT INTO orders
(orders.order_date, orders.arrival_date, orders.student_number)
VALUES (NOW(), NULL, :student_number);
SQL
        );
        $order->execute(array(':student_number' => $student_number));
        if ($order === false)
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