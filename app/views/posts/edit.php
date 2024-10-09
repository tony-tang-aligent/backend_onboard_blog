<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Edit Post</h1>
    <form action="/posts/<?= $post->id; ?>/update" method="POST">
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" name="title" value="<?= htmlspecialchars($post->title); ?>" required>
        </div>
        <div class="mb-3">
            <label for="body" class="form-label">Body</label>
            <textarea class="form-control" name="body" rows="5" required><?= htmlspecialchars($post->body); ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update Post</button>
        <a href="/" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
