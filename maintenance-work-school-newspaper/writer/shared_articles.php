<?php 
require_once 'classloader.php';

if (!$userObj->isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

$sharedArticles = $articleObj->getAllSharedArticles($user_id);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" />
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>

    <title>Shared Articles</title>
    
    <style>
        body {  
            font-family: "Garamond"; 
        }
        .updateForm { 
            display: none; 
        }
    </style>
</head>
<body style="background-color: #CADCAE;">
    <?php include 'includes/navbar.php'; ?>

    <div class="container-fluid">
        <h3 style="font-family: Nerko One;" class="mt-4 display-5 text-center"> Articles Shared with You, Admin <span class="text-success"><?= htmlspecialchars($username) ?></span></h3>

        <div class="row justify-content-center">
            <div class="col-md-10">

                <?php if (empty($sharedArticles)): ?>
                    <p style="font-family: Nerko One; font-size: 25px;" class="text-center">No shared articles with you at the moment.</p>
                <?php else: ?>

                    <?php foreach ($sharedArticles as $article): ?>
                        <div class="card mt-4 shadow">
                            <div class="card-body">
                                <?php if (!empty($article['image_url'])): ?>
                                    <img src="../<?= htmlspecialchars($article['image_url'], ENT_QUOTES, 'UTF-8') ?>" 
                                    alt="Article Header" class="img-fluid mb-3" style="width: 100%; height: 350px; object-fit: cover;" />
                                <?php endif; ?>

                                <h1 class="text-center" style="font-family: Anton, sans-serif;"><?php echo $article['title']; ?></h1>
                                <small class="d-block text-center my-1">
                                    <strong class="text-success"><?= htmlspecialchars($article['username']) ?></strong> - <?= $article['created_at'] ?>
                                </small>
                                <p class="text-justify" style="font-size: 15px; font-family: Georgia">
                                    y<?= nl2br(htmlspecialchars($article['content'])) ?>
                                </p>

                                <!-- Edit Article Form (Initially Hidden) -->
                                <div class="updateForm" id="updateForm_<?= $article['article_id'] ?>">
                                    <h4>Edit the Article</h4>
                                    <form action="core/handleForms.php" method="POST">
                                    <div class="form-group">
                                    <input type="text" name="title" class="form-control" id="editTitle_<?= $article['article_id'] ?>" value="<?= htmlspecialchars($article['title']) ?>" required />
                                </div>
                                <div class="form-group">
                                    <textarea name="description" class="form-control" rows="8" id="editDescription_<?= $article['article_id'] ?>" required><?= htmlspecialchars($article['content']) ?></textarea>
                                    </div>
                                    <input type="hidden" name="article_id" value="<?= $article['article_id'] ?>" />
                                    <div class="d-flex justify-content-end">
                                        <input type="submit" class="btn btn-primary" name="saveArticleBtn" value="Save Changes" />
                                    </div>
                                    </form>
                                </div>
                            </div>

                            <div class="card-footer d-flex mx-2 justify-content-end">
                                <button type="button" class="btn btn-success editButton" data-article-id="<?= $article['article_id'] ?>">Edit Article</button>
                            </div>
                        </div>  
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('.editButton').on('click', function() {
                var articleId = $(this).data('article-id');
                var updateForm = $('#updateForm_' + articleId);
                updateForm.toggleClass('updateForm');
            });
        });

    </script>
</body>
</html>
