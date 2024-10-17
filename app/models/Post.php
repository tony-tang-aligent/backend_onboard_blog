<?php
namespace app\models;
use app\core\Database;
use app\interfaces\EntityInterface;
use app\utils\DbUtils;

class Post implements EntityInterface {
    protected Database $db;

    public function __construct() {
        $this->db = new Database();
    }


    /** Model Create a new post
     * Dynamically alter the database schema
     * @param array $data
     * @return void
     */
    public function create(array $data): void
    {
        //extracting all the existing columns of the given table
        $existingColumns = $this->getTableColumns('Posts');
        //loop through the data to see if there is any new column
        foreach ($data as $column => $value) {
            if (!in_array($column, $existingColumns)) {
                // Add the new column to the table with a default data type (VARCHAR in this case)
                $sql = "ALTER TABLE Posts ADD $column VARCHAR(255) DEFAULT NULL";
                $this->db->query($sql);
                $this->db->execute();
            }
        }
        // Proceed with the normal INSERT query using all columns in $data.
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_map(fn($col) => ":$col", array_keys($data)));

        $sql = "INSERT INTO Posts ($columns, created_at) VALUES ($placeholders, NOW())";
        $this->db->query($sql);

        // Bind all parameters dynamically
        foreach ($data as $column => $value) {
            $this->db->bind(":$column", $value);
        }

        $this->db->execute();

    }

    /** Get all the columns of the table
     * @return array
     */
    private function getTableColumns(): array {
        return DbUtils::getTableColumns('Posts');
    }

    /** Model Update the post
     * @param int $id
     * @param array $data
     * @return void
     */
    public function update(int $id, array $data): void
    {
        //get the existing columns
        $existingColumns = $this->getTableColumns();

        // Loop through the data to see if there are any new columns
        foreach ($data as $column => $value) {
            if (!in_array($column, $existingColumns)) {
                // Add the new column to the table with a default data type (VARCHAR in this case)
                $sql = "ALTER TABLE Posts ADD $column VARCHAR(255) DEFAULT NULL";
                $this->db->query($sql);
                $this->db->execute();
            }
        }

        // Prepare the UPDATE SQL query dynamically
        $setClause = [];
        foreach ($data as $column => $value) {
            $setClause[] = "$column = :$column";
        }
        $setClauseString = implode(', ', $setClause);

        $sql = "UPDATE Posts SET $setClauseString, updated_at = NOW() WHERE id = :id";
        $this->db->query($sql);

        // Bind all parameters dynamically
        foreach ($data as $column => $value) {
            $this->db->bind(":$column", $value);
        }
        // Bind the ID parameter
        $this->db->bind(':id', $id);

        $this->db->execute();
    }

    /** Model delete a post
     * @param int $id
     * @return void
     */
    public function delete(int $id): void
    {
        $sql = "DELETE FROM Posts WHERE id = :id";
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        $this->db->execute();
    }

    /** Model Get all the posts
     * @return mixed
     */
    // Retrieve all blog posts from the database
    public function getAll(): mixed
    {
        $this->db->query("SELECT * FROM Posts ORDER BY created_at DESC");
        return $this->db->resultSet();
    }

    /** Model Retrieve a single blog post by ID
     * @param $id
     * @return mixed
     */
    public function getByID($id): mixed
    {
        $this->db->query("SELECT * FROM Posts WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
}

