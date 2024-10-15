<?php
namespace app\interfaces;

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
}
