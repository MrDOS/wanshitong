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
}