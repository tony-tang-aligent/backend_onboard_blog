<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Blog Posts</title>
<!--    <link href="../../public/css/popper.min.css" rel="stylesheet">-->
<!--    <script src="../../public/js/popper.min.js"></script>-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="d-flex justify-content-between">
        <h1>Latest Blog Posts</h1>
        <a href="/logout" class="btn btn-danger me-2">Logout</a>
<!--        <a href="/signup" class="btn btn-primary me-2">Signup</a>-->
<!--        <a href="/login" class="btn btn-secondary me-2">Login</a>-->
        <!-- Add Post button (only visible if logged in) -->
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addPostModal">Create Post</button>

<!--        --><?php
//        var_dump($_SESSION);
//        echo "fwqfqwf";
//        ?>
    </div>

    <div class="row mt-4">
        <!-- Loop through the posts array -->
        <?php if (!empty($posts)): ?>
            <?php foreach ($posts as $post): ?>
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($post->title); ?></h5>
                            <p class="card-text"><?= htmlspecialchars(substr($post->body, 0, 75)); ?>...</p>
                            <p class="card-text">
                                <small class="text-muted">Created: <?= htmlspecialchars($post->created_at); ?></small>
                            </p>
                            <a href="/posts/<?= $post->id; ?>" class="btn btn-primary">Read More</a>
                            <span class="badge bg-secondary float-end"><?= htmlspecialchars($post->comment_count); ?> Comments</span>

                            <!-- Display Edit and Delete buttons only if the logged-in user owns the post -->
                            <?php if ($_SESSION['user_id'] === $post->user_id): ?>
                                <a href="/posts/<?= $post->id; ?>/edit" class="btn btn-warning mt-2">Edit</a>
                                <form action="/posts/<?= $post->id; ?>/delete" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this post?');">
                                    <button type="submit" class="btn btn-danger mt-2">Delete</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-muted">No posts available at the moment.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Modal for adding a new post -->
<div class="modal fade" id="addPostModal" tabindex="-1" aria-labelledby="addPostModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/posts/create" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPostModalLabel">Add New Post</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="postTitle" class="form-label">Title</label>
                        <input type="text" class="form-control" id="postTitle" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="postBody" class="form-label">Body</label>
                        <textarea class="form-control" id="postBody" name="body" rows="5" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save Post</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

</body>
</html>
