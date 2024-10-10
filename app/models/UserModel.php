<?php

class UserModel extends Database
{
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

    public function findUserByUsername($username)
    {
        $this->query("SELECT * FROM Users WHERE username = :username");
        $this->bind(':username', $username);
        return $this->single();
    }

    public function findUserByEmail($email)
    {
        $this->query("SELECT * FROM Users WHERE email = :email");
        $this->bind(':email', $email);
        return $this->single();
    }

    public function findAllUsers() {
        $this->query("SELECT * FROM Users");
        return $this->resultSet();
    }

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


    public function findUserByID($id)
    {
        $query = "SELECT * FROM Users WHERE id = :id";
        $this->query($query);
        $this->bind(':id', $id);

        return $this->single();
    }

    public function deleteUserByID($id)
    {
        $this->query("DELETE FROM Users WHERE id = :id");
        $this->bind(':id', $id);
        return $this->execute();
    }
}

