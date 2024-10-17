<?php
namespace app\models;

class User extends AbstractEntity
{
    /** Model Retrieve a user by username
     * @param $username
     * @return mixed
     */
    public function getUserByUsername($username)
    {
        $this->db->query("SELECT * FROM Users WHERE username = :username");
        $this->db->bind(':username', $username);
        return $this->db->single();
    }

    /** Model Retrieve a user by email
     * @param $email
     * @return mixed
     */
    public function getUserByEmail($email)
    {
        $this->db->query("SELECT * FROM Users WHERE email = :email");
        $this->db->bind(':email', $email);
        return $this->db->single();
    }

    protected function getTableName(): string
    {
        return 'Users';
    }
}

