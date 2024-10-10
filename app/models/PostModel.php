<?php

require_once 'core/Database.php';
class PostModel {
    private $db;

    public function __construct() {
        // Create a new database connection using the Database class
        $this->db = new Database();
    }

    public function addPost($title, $body, $userId) {
        // Prepare the SQL query to insert a new blog post
        $sql = "INSERT INTO Posts (title, body, user_id, created_at) 
                VALUES (:title, :body, :userId, NOW())";

        // Prepare the statement
        $this->db->query($sql);

        // Bind the parameters to prevent SQL injection
        $this->db->bind(':title', $title);
        $this->db->bind(':body', $body);
        $this->db->bind(':userId', $userId);

        // Execute the query
        return $this->db->execute();
    }

    public function updatePost($id, $title, $body) {
        $sql = "UPDATE Posts SET title = :title, body = :body, updated_at = NOW() WHERE id = :id";
        $this->db->query($sql);
        $this->db->bind(':title', $title);
        $this->db->bind(':body', $body);
        $this->db->bind(':id', $id);
        //$this->db->bind(':userId', $_SESSION['user_id']);

        return $this->db->execute();
    }


    // Retrieve all blog posts from the database
    public function getPosts() {
        $this->db->query("SELECT * FROM Posts ORDER BY created_at DESC");
        return $this->db->resultSet();
    }

    // Retrieve a single blog post by ID
    public function getPostByID($id) {
        $this->db->query("SELECT * FROM Posts WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getCommentByID($id)
    {
        $this->db->query("SELECT * FROM Comments WHERE post_id = :id");
        $this->db->bind(':id', $id);
        return $this->db->resultSet();
    }

    public function incrementCommentCount($postId) {
        $this->db->query("UPDATE Posts Set comment_count = comment_count + 1 WHERE id= :id");
        $this->db->bind(':id', $postId);
        $this->db->execute();
    }

    public function decrementCommentCount($postId) {
        $sql = "UPDATE Posts SET comment_count = comment_count - 1 WHERE id = :id";
        $this->db->query($sql);
        $this->db->bind(':id', $postId);

        return $this->db->execute();
    }


    public function deletePost($id) {
        $sql = "DELETE FROM Posts WHERE id = :id";
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        //$this->db->bind(':userId', $_SESSION['user_id']);

        return $this->db->execute();
    }



}

