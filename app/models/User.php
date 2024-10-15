<?php
namespace app\models;

use app\core\Database;
use app\interfaces\EntityInterface;

class User implements EntityInterface
{
    protected Database $db;

    public function __construct() {
        $this->db = new Database();
    }
    /** Model Create a new user and insert into the database
     * @param array $data
     * @return void
     */
    public function create(array $data): void
    {
        // Hash the password before storing
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        $this->db->query("INSERT INTO Users (username, email, password) VALUES (:username, :email, :password)");
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $hashedPassword);
        $this->db->execute();
    }

    /** Model Retrieve a user by username
     * @param $username
     * @return mixed
     */
    public function findUserByUsername($username)
    {
        $this->db->query("SELECT * FROM Users WHERE username = :username");
        $this->db->bind(':username', $username);
        return $this->db->single();
    }

    /** Model Retrieve a user by email
     * @param $email
     * @return mixed
     */
    public function findUserByEmail($email)
    {
        $this->db->query("SELECT * FROM Users WHERE email = :email");
        $this->db->bind(':email', $email);
        return $this->db->single();
    }

    /** Model Find all users
     * @return mixed
     */
    public function findAllUsers() {
        $this->db->query("SELECT * FROM Users");
        return $this->db->resultSet();
    }

    /** Model Update the user information
     * @param $id
     * @param array $data
     * @return void
     */
    public function update($id, array $data): void
    {
        $query = "UPDATE Users SET username = :username, email = :email, password = :password, role = :role WHERE id = :id";
        $this->db->query($query);
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['hashedPassword']);
        $this->db->bind(':role', $data['role']);
        $this->db->bind(':id', $id);
        $this->db->execute();
    }


    /** Model Find a user by ID
     * @param $id
     * @return mixed
     */
    public function findUserByID($id)
    {
        $query = "SELECT * FROM Users WHERE id = :id";
        $this->db->query($query);
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    /** Model Delete a user by ID
     * @param $id
     * @return void
     */
    public function delete($id): void
    {
        $this->db->query("DELETE FROM Users WHERE id = :id");
        $this->db->bind(':id', $id);
        $this->db->execute();
    }
}

