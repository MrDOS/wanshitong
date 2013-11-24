<?php namespace wanshitong\models;

/**
 * A general interface for database repository classes.
 *
 * @author scoleman
 * @version 1.0.0
 * @since 1.0.0
 */
interface Repository
{
    /**
     * Construct the repository such that it connects to a given database.
     *
     * @param \PDO $db the database
     */
    public function __construct(\PDO $db);
}