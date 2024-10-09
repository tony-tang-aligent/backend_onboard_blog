<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($post->title); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1><?= htmlspecialchars($post->title); ?></h1>
    <p class="text-muted">Created: <?= htmlspecialchars($post->created_at); ?></p>

    <div class="mb-5">
        <p><?= nl2br(htmlspecialchars($post->body)); ?></p> <!-- Use nl2br to preserve line breaks -->
    </div>

    <!-- Comments Section -->
    <h3>Comments</h3>
    <ul class="list-group mb-5">
        <?php if (isset($comments) && is_array($comments) && !empty($comments)): ?>
            <?php foreach ($comments as $comment): ?>
                <li class="list-group-item">
                    <strong><?= htmlspecialchars(isset($comment->name) ? $comment->name : 'Anonymous'); ?></strong>
                    <span class="text-muted">on <?= htmlspecialchars($comment->created_at); ?></span>
                    <p><?= htmlspecialchars($comment->message); ?></p>

                    <!-- Display Edit and Delete buttons only if the logged-in user owns the comment -->
                    <?php if ($_SESSION['user_id'] === $comment->user_id): ?>
                        <a href="/comments/<?= $comment->id; ?>/edit/<?= $post->id; ?>" class="btn btn-warning btn-sm">Edit</a>
                        </form>
                        <form action="/comments/<?= $comment->id; ?>/delete/<?= $post->id; ?>" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this comment?');">
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>

        <?php else: ?>
            <li class="list-group-item">No comments yet.</li>
        <?php endif; ?>
    </ul>

    <!-- Comment Form -->
    <h4>Leave a Comment</h4>
    <form action="/posts/<?= $post->id; ?>/comments" method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" class="form-control" placeholder="Your name (optional)">
        </div>
        <div class="mb-3">
            <label for="message" class="form-label">Comment</label>
            <textarea name="message" class="form-control" rows="3" maxlength="50" required></textarea>
            <small class="form-text text-muted">Max 50 characters.</small>
        </div>
        <button type="submit" class="btn btn-primary">Post Comment</button>
    </form>

    <a href="/" class="btn btn-secondary mt-4">Back to Home</a>
    <a href="/logout" class="btn btn-danger">Logout</a>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

</body>
</html>
