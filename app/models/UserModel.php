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
}

