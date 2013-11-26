<?php namespace wanshitong\models;

use \wanshitong\models\Repository;

/**
 * Authors repository.
 *
 * @author nwetmore
 * @version 1.0.0
 * @since 1.0.0
 */
class Authors implements Repository
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
     * Get all authors.
     *
     * @return stdClass[] all authors
     */
    public function getAuthors()
    {
        $authors = $this->db->prepare(<<<SQL
SELECT authors.author_id, authors.sort_name 
FROM authors
ORDER BY authors.sort_name;
SQL
        );
        $authors->execute();
        return $authors->fetchAll(\PDO::FETCH_OBJ);
    }

    /**
     * Insert an author.
     *
     * @param string $given_name the author's given name
     * @param string $family_name the author's family name
     */
    public function insertAuthor($given_name, $family_name)
    {
        $given_name = trim($given_name);
        $family_name = trim($family_name);
        if (empty($given_name))
            throw new \Exception("Given name cannot be empty");

        if (empty($family_name))
            throw new \Exception("Family name cannot be empty");

        $authors = $this->db->prepare(<<<SQL
INSERT INTO authors
(authors.given_name, authors.family_name, authors.sort_name, authors.display_name)
VALUES (:given_name, :family_name, 
CONCAT_WS(", ", :family_name, :given_name), 
CONCAT_WS(" ", :given_name, :family_name));
SQL
        );
        $authors->execute(array(
            ':given_name' => $given_name,
            ':family_name' => $family_name));
    }

    /**
     * Delete an author.
     *
     * @param string $author_id the author ID
     */
    public function deleteAuthor($author_id)
    {
        $authors = $this->db->prepare(<<<SQL
DELETE FROM book_authors
WHERE book_authors.author_id = :author_id;

DELETE FROM authors
WHERE authors.author_id = :author_id;
SQL
        );
        $authors->execute(array(':author_id' => $author_id));
    }

    /**
     * Associate an author with a book.
     *
     * @param int $author_id the author ID
     * @param string $isbn the 13-digit ISBN of the book
     */
    public function associateAuthorWithBook($author_id, $isbn)
    {
        $authors = $this->db->prepare(<<<SQL
INSERT INTO book_authors
(book_authors.author_id, book_authors.isbn)
VALUES (:author_id, :isbn);
SQL
        );
        $authors->execute(array(
            ':author_id' => $author_id,
            ':isbn' => $isbn));
    }

    /**
     * Disassociate an author from a book.
     *
     * @param int $author_id the author ID
     * @param string $isbn the 13-digit ISBN of the book
     */
    public function disassociateAuthorWithBook($author_id, $isbn)
    {
        $authors = $this->db->prepare(<<<SQL
DELETE FROM book_authors
WHERE book_authors.author_id = :author_id
AND book_authors.isbn = :isbn;
SQL
        );
        $authors->execute(array(
            ':author_id' => $author_id,
            ':isbn' => $isbn));
    }
}