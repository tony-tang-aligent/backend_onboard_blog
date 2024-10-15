<?php
namespace app\models;
class Post extends Entity {

    /** Model Add a post
     * @param $title
     * @param $body
     * @param $userId
     * @return null
     */
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

    /** Model Update a post
     * @param $id
     * @param $title
     * @param $body
     * @return null
     */
    public function updatePost($id, $title, $body) {
        $sql = "UPDATE Posts SET title = :title, body = :body, updated_at = NOW() WHERE id = :id";
        $this->db->query($sql);
        $this->db->bind(':title', $title);
        $this->db->bind(':body', $body);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }


    /** Model Get all the posts
     * @return mixed
     */
    // Retrieve all blog posts from the database
    public function getPosts() {
        $this->db->query("SELECT * FROM Posts ORDER BY created_at DESC");
        return $this->db->resultSet();
    }

    /** Model Retrieve a single blog post by ID
     * @param $id
     * @return mixed
     */
    public function getPostByID($id) {
        $this->db->query("SELECT * FROM Posts WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    /** Model Increasing the comment count
     * @param $postId
     * @return void
     */
    public function incrementCommentCount($postId) {
        $this->db->query("UPDATE Posts Set comment_count = comment_count + 1 WHERE id= :id");
        $this->db->bind(':id', $postId);
        $this->db->execute();
    }

    /** Model Decreasing the comment count
     * @param $postId
     * @return null
     */
    public function decrementCommentCount($postId) {
        $sql = "UPDATE Posts SET comment_count = comment_count - 1 WHERE id = :id";
        $this->db->query($sql);
        $this->db->bind(':id', $postId);
        return $this->db->execute();
    }

    /** Model Delete a post by ID
     * @param $id
     * @return null
     */
    public function deletePost($id) {
        $sql = "DELETE FROM Posts WHERE id = :id";
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }


    public function create(array $data): void
    {
        $sql = "INSERT INTO Posts (title, body, user_id, created_at) 
                VALUES (:title, :body, :userId, NOW())";

        $this->db->query($sql);
        // Bind parameters to prevent SQL injection
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':body', $data['body']);
        $this->db->bind(':userId', $data['userId']);
        $this->db->execute();
    }

    public function update(int $id, array $data): void
    {
        $sql = "UPDATE Posts SET title = :title, body = :body, updated_at = NOW() WHERE id = :id";
        $this->db->query($sql);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':body', $data['body']);
        $this->db->bind(':id', $id);
        $this->db->execute();
    }

    public function delete(int $id): void
    {
        $sql = "DELETE FROM Posts WHERE id = :id";
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        $this->db->execute();
    }
}

