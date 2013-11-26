<?php namespace wanshitong\models;

use \wanshitong\models\Repository;

/**
 * Books repository.
 *
 * @author nwetmore
 * @version 1.0.0
 * @since 1.0.0
 */
class Books implements Repository
{
    /**
     * @var \PDO
     */
    private $db;

    /**
     * @var boolean
     */
    private $show_unstocked;

    /**
     * Construct the repository.
     *
     * @param \PDO $db the database
     */
    public function __construct(\PDO $db)
    {
        $this->db = $db;
        $this->show_unstocked = false;
    }

    /**
     * Set whether or not unstocked books should be listed.
     */
    public function setShowUnstocked($show_unstocked)
    {
        $this->show_unstocked = ($show_unstocked) ? true : false;
    }

    /**
     * Get all books. Optionally limit by department, course, and section.
     *
     * @param string $department the department code (e.g., 'COMP', 'MATH')
     * @param string $course the course code (e.g., '3753', '1013')
     * @param string $section the section slot (e.g., 'X1', 'A1')
     * @return stdClass[] all stocked books
     */
    public function getBooks($department = null, $course = null, $section = null)
    {
        $query = <<<SQL
SELECT t1.isbn, t1.title, t1.stocked, t1.price, t1.quantity,
GROUP_CONCAT(DISTINCT t3.display_name ORDER BY t3.display_name SEPARATOR ', ') AS authors,
GROUP_CONCAT(DISTINCT CONCAT_WS(' ', t7.department_code, t6.course_number, t5.slot) ORDER BY t7.department_code, t6.course_number, t5.slot SEPARATOR ', ') AS courses
FROM books t1
LEFT JOIN book_authors t2 ON t2.isbn = t1.isbn
LEFT JOIN authors t3 ON t3.author_id = t2.author_id
LEFT JOIN section_books t4 ON t4.isbn = t1.isbn
LEFT JOIN sections t5 ON t5.section_number = t4.section_number
LEFT JOIN courses t6 ON t6.course_number = t4.course_number
LEFT JOIN departments t7 ON t7.department_code = t4.department_code

SQL;

        if ($this->show_unstocked)
            $query .= "WHERE 1 = 1\n";
        else
            $query .= "WHERE t1.stocked = true\n";

        $parameters = array();

        if ($department !== null)
        {
            $query .= "AND t7.department_code = :department\n";
            $parameters[':department'] = $department;

            if ($course !== null)
            {
                $query .= "AND t6.course_number = :course\n";
                $parameters[':course'] = $course;

                if ($section !== null)
                {
                    $query .= "AND t5.slot = :section\n";
                    $parameters[':section'] = $section;
                }
            }
        }
        
        $query .= <<<SQL
GROUP BY t1.isbn
ORDER BY t1.title;
SQL;
        $books = $this->db->prepare($query);
        $books->execute($parameters);
        return $books->fetchAll(\PDO::FETCH_OBJ);
    }

    /**
     * Get all stocked books by an author. The name passed should be either the
     * author's full name or display name.
     *
     * @param string $author the name of the author
     * @return stdClass[] all stocked books by the author
     */
    public function getBooksByAuthor($author)
    {
        $query = <<<SQL
SELECT t1.isbn, t1.title, t1.stocked, t1.price, t1.quantity,
GROUP_CONCAT(DISTINCT t3.display_name ORDER BY t3.display_name SEPARATOR ', ') AS authors,
GROUP_CONCAT(DISTINCT CONCAT_WS(' ', t7.department_code, t6.course_number, t5.slot) ORDER BY t7.department_code, t6.course_number, t5.slot SEPARATOR ', ') AS courses
FROM books t1
LEFT JOIN book_authors t2 ON t2.isbn = t1.isbn
LEFT JOIN authors t3 ON t3.author_id = t2.author_id
LEFT JOIN section_books t4 ON t4.isbn = t1.isbn
LEFT JOIN sections t5 ON t5.section_number = t4.section_number
LEFT JOIN courses t6 ON t6.course_number = t4.course_number
LEFT JOIN departments t7 ON t7.department_code = t4.department_code

SQL;

        if ($this->show_unstocked)
            $query .= "WHERE 1 = 1\n";
        else
            $query .= "WHERE t1.stocked = true\n";

        $query .= <<<SQL
AND t3.sort_name = :author
OR t3.display_name = :author
GROUP BY t1.isbn
ORDER BY t1.title;
SQL;
        $books = $this->db->prepare($query);
        $books->execute(array(':author' => $author));
        return $books->fetchAll(\PDO::FETCH_OBJ);
    }

    /**
     * Get a book by ISBN.
     *
     * @param string $isbn the 13-digit ISBN of the book
     * @return stdClass the book
     */
    public function getBookByISBN($isbn)
    {
        $books = $this->db->prepare(<<<SQL
SELECT t1.isbn, t1.title, t1.stocked, t1.price, t1.quantity,
GROUP_CONCAT(DISTINCT t3.display_name ORDER BY t3.display_name SEPARATOR ', ') AS authors,
GROUP_CONCAT(DISTINCT CONCAT_WS(' ', t7.department_code, t6.course_number, t5.slot) ORDER BY t7.department_code, t6.course_number, t5.slot SEPARATOR ', ') AS courses
FROM books t1
LEFT JOIN book_authors t2 ON t2.isbn = t1.isbn
LEFT JOIN authors t3 ON t3.author_id = t2.author_id
LEFT JOIN section_books t4 ON t4.isbn = t1.isbn
LEFT JOIN sections t5 ON t5.section_number = t4.section_number
LEFT JOIN courses t6 ON t6.course_number = t4.course_number
LEFT JOIN departments t7 ON t7.department_code = t4.department_code
WHERE t1.isbn = :isbn
GROUP BY t1.isbn;
SQL
        );
        $books->execute(array(':isbn' => $isbn));
        return $books->fetch(\PDO::FETCH_OBJ);
    }

    /**
     * Get all books required by a student.
     *
     * @param string $student_number the student's number
     * @return stdClass[] all books required by the student
     */
    public function getBooksByStudent($student_number)
    {
        $books = $this->db->prepare(<<<SQL
SELECT t7.department_code, t6.course_number, t5.slot, t1.isbn, t1.title, t1.stocked, t1.price,
GROUP_CONCAT(DISTINCT t3.display_name ORDER BY t3.display_name SEPARATOR ', ') AS authors,
GROUP_CONCAT(DISTINCT CONCAT_WS(' ', t7.department_code, t6.course_number, t5.slot) ORDER BY t7.department_code, t6.course_number, t5.slot SEPARATOR ', ') AS courses
FROM books t1 
LEFT JOIN book_authors t2 ON t2.isbn = t1.isbn
LEFT JOIN authors t3 ON t3.author_id = t2.author_id
LEFT JOIN section_books t4 ON t4.isbn = t1.isbn
LEFT JOIN sections t5 ON t5.section_number = t4.section_number
LEFT JOIN courses t6 ON t6.course_number = t4.course_number
LEFT JOIN departments t7 ON t7.department_code = t4.department_code
LEFT JOIN student_sections t8 ON t8.department_code = t7.department_code
WHERE t1.stocked = true
AND t8.section_number = t5.section_number
AND t8.course_number = t6.course_number
AND t8.student_number = :student_number
GROUP BY t1.isbn
ORDER BY t1.title;
SQL
        );
        $books->execute(array(':student_number' => $student_number));
        return $books->fetchAll(\PDO::FETCH_OBJ);
    }

    /**
     * Get all books included in an order.
     *
     * @param string $order_id the order ID
     * @return stdClass[] all books included in the order
     */
    public function getBooksByOrder($order_id)
    {
        $books = $this->db->prepare(<<<SQL
SELECT t1.isbn, t1.title, t1.stocked, t1.price, t8.quantity,
GROUP_CONCAT(DISTINCT t3.display_name ORDER BY t3.display_name SEPARATOR ', ') AS authors,
GROUP_CONCAT(DISTINCT CONCAT_WS(' ', t7.department_code, t6.course_number, t5.slot) ORDER BY t7.department_code, t6.course_number, t5.slot SEPARATOR ', ') AS courses
FROM books t1
LEFT JOIN book_authors t2 ON t2.isbn = t1.isbn
LEFT JOIN authors t3 ON t3.author_id = t2.author_id
LEFT JOIN section_books t4 ON t4.isbn = t1.isbn
LEFT JOIN sections t5 ON t5.section_number = t4.section_number
LEFT JOIN courses t6 ON t6.course_number = t4.course_number
LEFT JOIN departments t7 ON t7.department_code = t4.department_code
LEFT JOIN order_books t8 ON t8.isbn = t1.isbn
WHERE t8.order_id = :order_id
GROUP BY t1.isbn
ORDER BY t1.title;
SQL
        );
        $books->execute(array(':order_id' => $order_id));
        return $books->fetchAll(\PDO::FETCH_OBJ);
    }

    /**
     * Set the stock of a book.
     *
     * @param string $isbn the 13-digit ISBN of the book
     * @param int $stock the stock level (>= 0)
     */
    public function setBookStock($isbn, $stock)
    {
        if ($stock < 0)
            throw new \Exception("Stock cannot be less than 0");

        $books = $this->db->prepare(<<<SQL
UPDATE books
SET books.quantity = :stock
WHERE books.isbn = :isbn;
SQL
        );
        $books->execute(array(':isbn' => $isbn, ':stock' => $stock));
    }

    /**
     * Increment the stock of a book by the given amount.
     *
     * @param string $isbn the 13-digit ISBN of the book
     * @param int $stock the stock level by which to increment (>= 0)
     */
    public function addBookStock($isbn, $stock)
    {
        if ($stock < 0)
            throw new \Exception("Cannot add negative stock");

        $books = $this->db->prepare(<<<SQL
UPDATE books
SET books.quantity = books.quantity + :stock
WHERE books.isbn = :isbn;
SQL
        );
        $books->execute(array(':isbn' => $isbn, ':stock' => $stock));
    }

    /**
     * Set whether or not the book is stocked.
     *
     * @param string $isbn the 13-digit ISBN of the book
     * @param boolean $stocked whether or not the book is stocked
     */
    public function setStocked($isbn, $stocked)
    {
        $books = $this->db->prepare(<<<SQL
UPDATE books
SET books.stocked = :stocked
WHERE books.isbn = :isbn;
SQL
        );
        $books->execute(array(':isbn' => $isbn, ':stocked' => $stocked));
    }

    /**
     * Insert a book.
     *
     * @param string $isbn the 13-digit ISBN of the book
     * @param string $title the book title
     * @param int $stock the stock level (>= 0)
     * @param int $price the price of the book in cents
     */
    public function insertBook($isbn, $title, $stock, $price)
    {
        if ($stock < 0)
            throw new \Exception("Stock cannot be less than 0");

        if ($price < 0)
            throw new \Exception("Price cannot be less than 0");

        $books = $this->db->prepare(<<<SQL
INSERT INTO books
(books.isbn, books.title, books.quantity, books.price)
VALUES (:isbn, :title, :stock, :price)
ON DUPLICATE KEY UPDATE title = VALUES(title), quantity = VALUES(quantity), price = VALUES(price);
SQL
        );
        $books->execute(array(
            ':isbn' => $isbn,
            ':title' => $title,
            ':stock' => $stock,
            ':price' => $price));
    }
}