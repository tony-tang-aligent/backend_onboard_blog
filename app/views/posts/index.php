<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Posts</title>
<!--    <link href="../../public/css/popper.min.css" rel="stylesheet">-->
<!--    <script src="../../public/js/popper.min.js"></script>-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Blog Posts</h1>

    <div class="row">
        <?php foreach ($posts as $post): ?>
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title"><?= $post['title']; ?></h5>
                        <p class="card-text"><?= substr($post['body'], 0, 75); ?>...</p>
                        <p class="card-text">
                            <small class="text-muted">Created: <?= $post['created_at']; ?></small>
                            <?php if ($post['edited_at']): ?>
                                <small class="text-muted"> | Edited: <?= $post['edited_at']; ?></small>
                            <?php endif; ?>
                        </p>
                        <a href="/posts/<?= $post['id']; ?>" class="btn btn-primary">Read More</a>
                        <span class="badge bg-secondary float-end"><?= $post['comments_count']; ?> Comments</span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
