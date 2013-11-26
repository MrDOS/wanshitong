<?php namespace wanshitong\models;

use \wanshitong\models\Repository;

/**
 * Departments repository.
 *
 * @author nwetmore
 * @version 1.0.0
 * @since 1.0.0
 */
class Departments implements Repository
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
     * Get all departments.
     *
     * @return stdClass[] all departments
     */
    public function getDepartments()
    {
        $departments = $this->db->prepare(<<<SQL
SELECT departments.department_code 
FROM departments
ORDER BY departments.department_code;
SQL
        );
        $departments->execute();
        return $departments->fetchAll(\PDO::FETCH_OBJ);
    }

    /**
     * Get all courses in a department.
     *
     * @param string $department the department code (e.g., 'COMP', 'MATH')
     * @return stdClass[] all courses in the department
     */
    public function getCoursesByDepartment($department)
    {
        $departments = $this->db->prepare(<<<SQL
SELECT t1.department_code, t2.course_number
FROM departments t1
LEFT JOIN courses t2 ON t2.department_code = t1.department_code
WHERE t1.department_code = :department
ORDER BY t1.department_code, t2.course_number;
SQL
        );
        $departments->execute(array(':department' => $department));
        return $departments->fetchAll(\PDO::FETCH_OBJ);
    }

    /**
     * Get all courses in a department.
     *
     * @param string $department the department code (e.g., 'COMP', 'MATH')
     * @param string $course the course code (e.g., '3753', '1013')
     * @return stdClass[] all sections for the course
     */
    public function getSectionsByCourse($department, $course)
    {
        $departments = $this->db->prepare(<<<SQL
SELECT t1.department_code, t2.course_number, t3.slot
FROM departments t1
LEFT JOIN courses t2 ON t2.department_code = t1.department_code
LEFT JOIN sections t3 ON t3.course_number = t2.course_number
WHERE t1.department_code = :department
AND t2.course_number = :course
ORDER BY t1.department_code, t2.course_number, t3.slot;
SQL
        );
        $departments->execute(array(':department' => $department, ':course' => $course));
        return $departments->fetchAll(\PDO::FETCH_OBJ);
    }

    /**
     * Get all sections for the current year.
     *
     * @return stdClass[] all sections for the current year
     */
    public function getCurrentSections()
    {
        $departments = $this->db->prepare(<<<SQL
SELECT sections.department_code, sections.course_number, sections.slot, sections.section_number
FROM sections
WHERE sections.year = YEAR(NOW())
ORDER BY sections.department_code, sections.course_number, sections.slot;
SQL
        );
        $departments->execute();
        return $departments->fetchAll(\PDO::FETCH_OBJ);
    }

    /**
     * Get a section by its unique identifier.
     *
     * @param int $section_number the section number
     * @return stdClass the section
     */
    public function getSectionByNumber($section_number)
    {
        $departments = $this->db->prepare(<<<SQL
SELECT sections.department_code, sections.course_number, sections.slot, sections.section_number
FROM sections
WHERE sections.section_number = :section_number;
SQL
        );
        $departments->execute(array(':section_number' => $section_number));
        return $departments->fetch(\PDO::FETCH_OBJ);
    }

    /**
     * Associate a course section with a book.
     *
     * @param string $section_number the section number
     * @param string $isbn the 13-digit ISBN of the book
     */
    public function associateSectionWithBook($section_number, $isbn)
    {
        $section = $this->getSectionByNumber($section_number);
        if ($section === false) return false;

        $relation = $this->db->prepare(<<<SQL
INSERT INTO section_books
(section_books.department_code, section_books.course_number, section_books.section_number, section_books.isbn)
VALUES (:department_code, :course_number, :section_number, :isbn);
SQL
        );
        $relation->execute(array(
            ':department_code' => $section->department_code,
            ':course_number' => $section->course_number,
            ':section_number' => $section_number,
            ':isbn' => $isbn));
    }

    /**
     * Disassociate a course section from a book.
     *
     * @param string $section_number the section number
     * @param string $isbn the 13-digit ISBN of the book
     */
    public function disassociateSectionWithBook($section_number, $isbn)
    {
        $section = $this->getSectionByNumber($section_number);
        if ($section === false) return false;

        $relation = $this->db->prepare(<<<SQL
DELETE FROM section_books
WHERE section_books.department_code = :department_code
AND section_books.course_number = :course_number
AND section_books.section_number = :section_number
AND section_books.isbn = :isbn;
SQL
        );
        $relation->execute(array(
            ':department_code' => $section->department_code,
            ':course_number' => $section->course_number,
            ':section_number' => $section_number,
            ':isbn' => $isbn));
    }
}