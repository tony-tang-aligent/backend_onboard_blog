<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Comment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Edit Comment</h1>
    <form action="/comments/<?= $comment->id; ?>/posts/<?= $postId ?>/update" method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($comment->name); ?>" placeholder="Your name (optional)">
        </div>
        <div class="mb-3">
            <label for="message" class="form-label">Comment</label>
            <textarea class="form-control" name="message" rows="3" maxlength="50" required><?= htmlspecialchars($comment->message); ?></textarea>
            <small class="form-text text-muted">Max 50 characters.</small>
        </div>
        <button type="submit" class="btn btn-primary">Update Comment</button>

    </form>
    <a href="/posts/<?= $postId; ?>" class="btn btn-secondary">Cancel</a>
<!--    <a href="/" class="btn btn-secondary">Cancel</a>-->
</div>

<!-- Bootstrap JS -->

</body>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

</html>
