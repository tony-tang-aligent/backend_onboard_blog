<?php
namespace app\models;
use app\core\Database;

class Comment {
    private Database $db;

    public function __construct() {
        $this->db = new Database();
    }

    /** Model add a post to the DB
     * @param $postId
     * @param $name
     * @param $message
     * @param $userId
     * @return null
     */
    public function add($postId, $name, $message, $userId) {
        $this->db->query('INSERT INTO Comments (post_id, name, message, user_id, created_at, status) VALUES (:post_id,:name, :message, :userId, NOW(), "pending")');
        $this->db->bind('post_id', $postId);
        $this->db->bind(':name', $name);
        $this->db->bind(':message',$message);
        $this->db->bind(':userId', $userId);
        return $this->db->execute();
    }

    /** Model delete a post from the DB
     * @param $commentId
     * @return null
     */
    public function delete($commentId) {
        $sql = "DELETE FROM Comments WHERE id = :id";
        $this->db->query($sql);
        $this->db->bind(':id', $commentId);

        return $this->db->execute();
    }

    /** Model get a comment based on its ID
     * @param $id
     * @return mixed
     */
    public function getCommentByID($id)
    {
        $this->db->query("SELECT * FROM Comments WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    /** Model update a comment based on its id
     * @param $commentId
     * @param $name
     * @param $message
     * @return null
     */
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

    /** Model get all pending comments
     * @return mixed
     */
    public function getAllPendingComments()
    {
        $this->db->query("SELECT * FROM Comments WHERE status='pending'");
        return $this->db->resultSet();
    }

    /** Model get all approved comments
     * @param $id
     * @return mixed
     */
    public function getApprovedComments($id)
    {
        $this->db->query("SELECT * FROM Comments WHERE post_id = :id AND status='approved'");
        $this->db->bind(':id', $id);
        return $this->db->resultSet();
    }

    /** Model admin user update the status of a comment
     * @param $commentId
     * @param $status
     * @return null
     */
    public function updateCommentStatus($commentId, $status)
    {
        $this->db->query("UPDATE Comments SET status = :status WHERE id = :commentId");
        $this->db->bind(':status', $status);
        $this->db->bind(':commentId', $commentId);
        return $this->db->execute();
    }
}