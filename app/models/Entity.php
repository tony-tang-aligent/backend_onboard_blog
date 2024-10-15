<?php
namespace app\models;

use app\core\Database;

abstract class Entity {
    protected Database $db;

    public function __construct() {
        $this->db = new Database();
    }

    /**
     * Create a new record in the database.
     * @param array $data
     * @return void
     */
    abstract public function create(array $data): void;

    /**
     * Update an existing record in the database.
     * @param int $id
     * @param array $data
     * @return void
     */
    abstract public function update(int $id, array $data): void;

    /**
     * Delete a record from the database.
     * @param int $id
     * @return void
     */
    abstract public function delete(int $id): void;
}
