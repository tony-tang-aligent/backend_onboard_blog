<?php

require_once 'core/Database.php';
class PostModel {
    private $db;

    public function __construct() {
        // Create a new database connection using the Database class
        $this->db = new Database();
    }

    // Add a new blog post to the database
    public function addPost($title, $body, $user_id) {
        // SQL query to insert a new blog post
        $this->db->query("INSERT INTO Posts (title, body, created_at) 
                          VALUES (:title, :body, NOW())");

        // Bind the parameters to prevent SQL injection
        $this->db->bind(':title', $title);
        $this->db->bind(':body', $body);
        //$this->db->bind(':user_id', 1);

        // Execute the query and return the result
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
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


}

