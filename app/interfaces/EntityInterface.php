<?php
namespace app\interfaces;

use PDO;

interface EntityInterface {
    /**
     * Create a new record in the database.
     * @param array $data
     * @return void
     */
    public function create(array $data): void;

    /**
     * Update an existing record in the database.
     * @param int $id
     * @param array $data
     * @return void
     */
    public function update(int $id, array $data): void;

    /**
     * Delete a record from the database.
     * @param int $id
     * @return void
     */
    public function delete(int $id): void;

    /** Retrieve a record by its id
     * @param int $id
     * @return mixed
     */
    public function getByID(int $id): mixed;

    /** Retrieve all the records of an item
     * @return mixed
     */
    public function getAll(): mixed;
}
