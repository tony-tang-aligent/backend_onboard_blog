<?php

namespace app\models;

use app\core\Database;
use app\interfaces\EntityInterface;
use app\utils\DbUtils;

abstract class AbstractEntity implements EntityInterface {
    protected Database $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    /** Create record in the database
     * @param array $data
     * @return void
     */
    public function create(array $data): void {
        $table = $this->getTableName();

        // Dynamically build the SQL for INSERT
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_map(fn($col) => ":$col", array_keys($data)));

        $sql = "INSERT INTO $table ($columns, created_at) VALUES ($placeholders, NOW())";
        $this->db->query($sql);

        // Bind values dynamically
        foreach ($data as $column => $value) {
            $this->db->bind(":$column", $value);
        }

        $this->db->execute();
    }

    /** Retrieve all records
     * @return mixed
     */
    public function getAll(): mixed {
        $table = $this->getTableName();
        $this->db->query("SELECT * FROM $table ORDER BY created_at DESC");
        return $this->db->resultSet();
    }

    /** Retrieve a single record by ID
     * @param int $id
     * @return mixed
     */
    public function getByID(int $id): mixed {
        $table = $this->getTableName();
        $this->db->query("SELECT * FROM $table WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    /** Update record
     * @param int $id
     * @param array $data
     * @return void
     */
    public function update(int $id, array $data): void {
        $table = $this->getTableName();
        $setClause = implode(', ', array_map(fn($col) => "$col = :$col", array_keys($data)));

        $sql = "UPDATE $table SET $setClause, updated_at = NOW() WHERE id = :id";
        $this->db->query($sql);

        // Bind the values dynamically
        foreach ($data as $column => $value) {
            $this->db->bind(":$column", $value);
        }
        $this->db->bind(':id', $id);

        $this->db->execute();
    }

    /** Delete record
     * @param int $id
     * @return void
     */
    public function delete(int $id): void {
        $table = $this->getTableName();
        $this->db->query("DELETE FROM $table WHERE id = :id");
        $this->db->bind(':id', $id);
        $this->db->execute();
    }

    /** Common function to get table columns using DbUtils
     * @return array
     */
    protected function getTableColumns(): array {
        $table = $this->getTableName();
        return DbUtils::getTableColumns($table);
    }

    /** Each model should implement this to specify the table name
     * @return string
     */
    abstract protected function getTableName(): string;

}