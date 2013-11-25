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
}