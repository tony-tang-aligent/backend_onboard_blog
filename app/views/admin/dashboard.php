<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Admin Dashboard</h1>

    <!-- Posts Management Section -->
    <h3>Manage Posts</h3>
    <a href="/admin/posts/create" class="btn btn-success mb-3">Create New Post</a>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Title</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($posts as $post): ?>
            <tr>
                <td><?= $post['title']; ?></td>
                <td><?= $post['created_at']; ?></td>
                <td>
                    <a href="/admin/posts/<?= $post['id']; ?>/edit" class="btn btn-warning btn-sm">Edit</a>
                    <form action="/admin/posts/<?= $post['id']; ?>/delete" method="POST" style="display:inline;">
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Comments Management Section -->
    <h3>Manage Comments</h3>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Comment</th>
            <th>Post</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($comments as $comment): ?>
            <tr>
                <td><?= $comment['message']; ?></td>
                <td><a href="/posts/<?= $comment['post_id']; ?>"><?= $comment['post_title']; ?></a></td>
                <td><?= $comment['created_at']; ?></td>
                <td>
                    <a href="/admin/comments/<?= $comment['id']; ?>/approve" class="btn btn-primary btn-sm">Approve</a>
                    <form action="/admin/comments/<?= $comment['id']; ?>/delete" method="POST" style="display:inline;">
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
