<?php namespace wanshitong\models;

use \wanshitong\models\Repository;

/**
 * Staff access control.
 *
 * @author nwetmore
 * @version 1.0.0
 * @since 1.0.0
 */
class Staff implements Repository
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
     * Get a staff member by username.
     *
     * @param string $username the staff member's username
     * @return stdClass the staff member
     */
    public function getStaffByUsername($username)
    {
        $staff = $this->db->prepare(<<<SQL
SELECT staff.username, staff.password, staff.password_salt
FROM staff
WHERE staff.username = :username;
SQL
        );
        $staff->execute(array(':username' => $username));
        return $staff->fetch(\PDO::FETCH_OBJ);
    }

    /**
     * Determine whether or not the user is logged in.
     *
     * @return boolean whether or not the user is logged in
     */
    public static function isLoggedIn()
    {
        return (isset($_SESSION['user']));
    }
}