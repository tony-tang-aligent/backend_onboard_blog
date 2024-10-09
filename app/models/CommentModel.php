<?php
require_once 'core/Database.php';
class CommentModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function addComment($postId, $name, $message) {
        $this->db->query('INSERT INTO Comments (post_id, name, message, created_at) VALUES (:post_id,:name, :message, NOW())');
        $this->db->bind('post_id', $postId);
        $this->db->bind(':name', $name);
        $this->db->bind(':message',$message);
        return $this->db->execute();
    }
}