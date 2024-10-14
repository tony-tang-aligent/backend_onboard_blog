<?php
namespace app\models;
use app\core\Database;

class User extends Database
{
    /** Model Create a new user and insert into the database
     * @param $username
     * @param $email
     * @param $password
     * @return null
     */
    public function register($username, $email, $password)
    {
        // Hash the password before storing
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $this->query("INSERT INTO Users (username, email, password) VALUES (:username, :email, :password)");
        $this->bind(':username', $username);
        $this->bind(':email', $email);
        $this->bind(':password', $hashedPassword);
        return $this->execute();
    }

    /** Model Retrieve a user by username
     * @param $username
     * @return mixed
     */
    public function findUserByUsername($username)
    {
        $this->query("SELECT * FROM Users WHERE username = :username");
        $this->bind(':username', $username);
        return $this->single();
    }

    /** Model Retrieve a user by email
     * @param $email
     * @return mixed
     */
    public function findUserByEmail($email)
    {
        $this->query("SELECT * FROM Users WHERE email = :email");
        $this->bind(':email', $email);
        return $this->single();
    }

    /** Model Find all users
     * @return mixed
     */
    public function findAllUsers() {
        $this->query("SELECT * FROM Users");
        return $this->resultSet();
    }

    /** Model Update the user information
     * @param $id
     * @param $username
     * @param $email
     * @param $hashedPassword
     * @param $role
     * @return null
     */
    public function updateUser($id, $username, $email, $hashedPassword, $role) {
        $query = "UPDATE Users SET username = :username, email = :email, password = :password, role = :role WHERE id = :id";
        $this->query($query);
        $this->bind(':username', $username);
        $this->bind(':email', $email);
        $this->bind(':password', $hashedPassword);
        $this->bind(':role', $role);
        $this->bind(':id', $id);
        return $this->execute();
    }


    /** Model Find a user by ID
     * @param $id
     * @return mixed
     */
    public function findUserByID($id)
    {
        $query = "SELECT * FROM Users WHERE id = :id";
        $this->query($query);
        $this->bind(':id', $id);
        return $this->single();
    }

    /** Model Delete a user by ID
     * @param $id
     * @return null
     */
    public function deleteUserByID($id)
    {
        $this->query("DELETE FROM Users WHERE id = :id");
        $this->bind(':id', $id);
        return $this->execute();
    }
}

