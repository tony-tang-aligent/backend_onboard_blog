<?php
require_once 'core/Database.php';
class CommentModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function addComment($postId, $name, $message, $userId) {
        $this->db->query('INSERT INTO Comments (post_id, name, message, user_id, created_at, status) VALUES (:post_id,:name, :message, :userId, NOW(), "pending")');
        $this->db->bind('post_id', $postId);
        $this->db->bind(':name', $name);
        $this->db->bind(':message',$message);
        $this->db->bind(':userId', $userId);
        return $this->db->execute();
    }

    public function deleteComment($commentId) {
        $sql = "DELETE FROM Comments WHERE id = :id";
        $this->db->query($sql);
        $this->db->bind(':id', $commentId);

        return $this->db->execute();
    }

    public function getCommentByID($id)
    {
        $this->db->query("SELECT * FROM Comments WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function updateComment($commentId, $name, $message) {
        // Prepare the SQL query
        $this->db->query("UPDATE Comments SET name = :name, message = :message WHERE id = :commentId");

        // Bind the parameters
        $this->db->bind(':name', $name);
        $this->db->bind(':message', $message);
        $this->db->bind(':commentId', $commentId);

        // Execute the query
        return $this->db->execute();
    }

    public function getAllComments()
    {
        $this->db->query("SELECT * FROM Comments WHERE status='pending'");
        return $this->db->resultSet();
    }

    public function getApprovedComments($id)
    {
        $this->db->query("SELECT * FROM Comments WHERE post_id = :id AND status='approved'");
        $this->db->bind(':id', $id);
        return $this->db->resultSet();
    }

    public function approveCommentStatus($commentId)
    {
        $this->db->query("UPDATE Comments SET status='approved' WHERE id = :commentId");
        $this->db->bind(':commentId', $commentId);
        return $this->db->execute();
    }

    public function rejectCommentStatus($commentId)
    {
        $this->db->query("UPDATE Comments SET status='rejected' WHERE id = :commentId");
        $this->db->bind(':commentId', $commentId);
        return $this->db->execute();
    }


}