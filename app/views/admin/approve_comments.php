<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approve Comments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Pending Comments</h1>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Comment</th>
            <th>Post</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($comments as $comment): ?>
            <tr>
                <td><?= htmlspecialchars($comment->message); ?></td>
                <td><a href="/admin/posts/<?= $comment->post_id; ?>">View Post</a></td>
                <td>
                    <a href="/admin/comments/<?= $comment->id; ?>/approve" class="btn btn-success btn-sm">Approve</a>
                    <a href="/admin/comments/<?= $comment->id; ?>/reject" class="btn btn-danger btn-sm">Reject</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <a href="/admin" class="btn btn-secondary mt-4">Back to Dashboard</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
