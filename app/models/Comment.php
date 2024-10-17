<?php
namespace app\models;
use app\core\Database;
use app\interfaces\EntityInterface;
use InvalidArgumentException;

class Comment implements EntityInterface
{
    protected Database $db;

    public function __construct() {
        $this->db = new Database();
    }
    /** API Create a new Post
     * @param array $data
     * @return void
     */
    public function create(array $data): void
    {
        $this->db->query('INSERT INTO Comments (post_id, name, message, user_id, created_at, status) VALUES (:post_id,:name, :message, :userId, NOW(), "pending")');
        $this->db->bind('post_id', $data['postId']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':message',$data['message']);
        $this->db->bind(':userId', $data['userId']);
        $this->db->execute();
    }

    /** Model delete a post from the DB
     * @param $id
     * @return void
     */
    public function delete($id): void
    {
        $sql = "DELETE FROM Comments WHERE id = :id";
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        $this->db->execute();
    }

    /** Model update a comment based on its id
     * @param $id
     * @param array $data
     * @return void
     */
    public function update($id, array $data): void
    {
        // Prepare the SQL query
        $this->db->query("UPDATE Comments SET name = :name, message = :message WHERE id = :commentId");

        // Bind the parameters
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':message', $data['message']);
        $this->db->bind(':commentId', $data['commentId']);

        $this->db->execute();
    }

    /** Model get a comment based on its ID
     * @param $id
     * @return mixed
     */
    public function getByID($id): mixed
    {
        $this->db->query("SELECT * FROM Comments WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
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

    /** Model Retrieve all the comments based on the status
     * @param $status
     * @return array
     */
    public function getCommentsByStatus($status): array
    {
        // Ensure that the status is valid before executing the query
        $allowedStatuses = ['pending', 'approved', 'rejected'];
        if (!in_array($status, $allowedStatuses)) {
            throw new InvalidArgumentException('Invalid status provided.');
        }

        $sql = "SELECT * FROM comments WHERE status = :status";
        $this->db->query($sql);
        $this->db->bind(':status', $status);

        return  $this->db->execute();
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

    /** Model Get the comment count for a specific post
     * @param int $postId
     * @return int
     */
    public function getCommentCount(int $postId): int {
        $this->db->query("SELECT COUNT(*) as comment_count FROM Comments WHERE post_id = :post_id");
        $this->db->bind(':post_id', $postId);
        $result = $this->db->single();
        return (int)$result->comment_count;
    }



}