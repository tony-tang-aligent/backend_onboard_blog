<?php
namespace app\models;
use InvalidArgumentException;

class Comment extends AbstractEntity
{
    /** Model get all pending comments
     * @return mixed
     */
    public function getAllPendingComments(): mixed
    {
        $this->db->query("SELECT * FROM Comments WHERE status='pending'");
        return $this->db->resultSet();
    }

    /** Model get all approved comments
     * @param $id
     * @return mixed
     */
    public function getApprovedComments($id): mixed
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

    protected function getTableName(): string
    {
        return 'Comments';
    }
}