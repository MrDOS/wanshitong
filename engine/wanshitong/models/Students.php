<?php namespace wanshitong\models;

use \wanshitong\models\Repository;

/**
 * Students repository.
 *
 * @author nwetmore
 * @version 1.0.0
 * @since 1.0.0
 */
class Students implements Repository
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
     * Get a student by number.
     *
     * @param string $student_number the student number
     * @return stdClass the student
     */
    public function getStudentByNumber($student_number)
    {
        $students = $this->db->prepare(<<<SQL
SELECT students.student_number, students.email
FROM students
WHERE students.student_number = :student_number
SQL
        );
        $students->execute(array(':student_number' => $student_number));
        return $students->fetch(\PDO::FETCH_OBJ);
    }
}