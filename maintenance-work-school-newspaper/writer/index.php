<?php 
require_once 'classloader.php'; 

if (!$userObj->isLoggedIn()) {
    header("Location: login.php");
    exit;
}

if ($userObj->isAdmin()) {
    header("Location: ../admin/index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];


$articles = $articleObj->getActiveArticles();
$notifications = $notificationObj->getEditRequestNotificationsByUser($user_id);


$notificationsByArticle = [];
foreach ($notifications as $notification) {
  $notificationsByArticle[$notification['article_id']] = $notification;
}
?>

<!doctype html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous" />
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>

  <!-- fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Anton&family=Nerko+One&family=Playfair+Display&display=swap" rel="stylesheet">

  <title>Writer Dashboard</title>

  <style>
    body { 
      font-family: "Garamond";
    }
  </style>
</head>
<body style="background-color: #CADCAE;">
  <?php include 'includes/navbar.php'; ?>

  <div class="container-fluid my-4">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="p-4" style="background-color: #fff; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);">
          <h4 class="mt-2 text-center" style="font-family: Nerko One;">
            Let your words paint the world anew, <span class="text-success"><?= htmlspecialchars($username) ?></span>!
          </h4>
          <?php  
            if (isset($_SESSION['message']) && isset($_SESSION['status'])) {

              if ($_SESSION['status'] == "200") {
                echo "<h6 class='text-center' style='color: green; font-size: 18px;'>{$_SESSION['message']}</h6>";
              }

              else {
                echo "<h6 class='text-center' style='color: red; font-size: 18px;'>{$_SESSION['message']}</h6>"; 
              }

            }
            unset($_SESSION['message']);
            unset($_SESSION['status']);
          ?>
          <form action="core/handleForms.php" method="POST" enctype="multipart/form-data" style="margin-top: 2rem;">
            <div class="form-group">
              <label for="image">Article Header:</label>
              <input type="file" class="form-control h-auto" name="image" accept="image/*" />
            </div>
            <div class="form-group">
              <input type="text" class="form-control" name="title" placeholder="Input title here" required />
            </div>
            <div class="form-group">
              <textarea name="description" class="form-control" placeholder="Submit an article!" rows="5" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary mt-3 w-50 d-block mx-auto" name="insertArticleBtn" style="font-family: Georgia; font-size: 16px;">
              Submit your Article
            </button>
          </form>
        </div>
      </div>

      <div class="container-fluid text-center mt-4 py-2" style="background-color: #355E3B;">
        <h2 style="color: #F7F4EA; font-family: Nerko One;" class="cute-header">Dive into insightful reads with our latest articles! 
          <span style="font-size: 20px; margin-left: 5px;">˶ᵔ ᵕ ᵔ˶</span>
        </h2>
      </div>


      <div class="col-md-10">
        <?php if (empty($articles)): ?>
          <div class="alert alert-warning text-center mt-5">
            <strong>No articles to show as of now.</strong>
          </div>
        <?php else: ?>
          <?php foreach ($articles as $article): ?>
            <div class="card mt-4 shadow">
              <div class="card-body">
                <h1 class="text-center" style="font-family: Anton, sans-serif;"><?= htmlspecialchars($article['title']) ?></h1>
                <small class="d-block text-center my-2">
                  <strong class="text-success"><?= htmlspecialchars($article['username']) ?></strong> - <?= $article['created_at'] ?>
                </small>

                <?php if (!empty($article['image_url'])): ?>
                  <img src="../<?= htmlspecialchars($article['image_url']) ?>" alt="Article Header"
                      class="img-fluid mb-3" style="width: 100%; height: 350px; object-fit: cover;" />
                <?php endif; ?>

                <?php if ($article['is_admin'] == 1): ?>
                  <p class="text-center"><small class="bg-primary text-white p-2">Message From Admin</small></p>
                <?php endif; ?>

                <p class="text-justify" style="font-size: 15px; font-family: Georgia;">
                  <?= nl2br(htmlspecialchars($article['content'])) ?>
                </p>
              </div>

              <?php
                $notification = $notificationsByArticle[$article['article_id']] ?? null;
                $showRequestButton = ($article['is_admin'] == 0 && $article['author_id'] != $user_id);
                $showEditButton = ($showRequestButton && $notification && $notification['notification_type'] === 'Edit Request Accepted');
              ?>

              <?php if ($showRequestButton || $showEditButton): ?>
                <div class="card-footer d-flex justify-content-end">
                  <?php if ($showRequestButton): ?>
                    <form action="core/handleForms.php" method="POST" class="mb-0 mr-2">
                      <input type="hidden" name="article_id" value="<?= $article['article_id'] ?>" />
                      <button type="submit" class="btn btn-warning" name="requestEditButton">Request Edit Access</button>
                    </form>
                  <?php endif; ?>

                  <?php if ($showEditButton): ?>
                    <form action="core/handleForms.php" method="POST" class="mb-0">
                      <input type="hidden" name="article_id" value="<?= $article['article_id'] ?>" />
                      <button type="submit" class="btn btn-success" name="editArticleButton">Edit Article</button>
                    </form>
                  <?php endif; ?>
                </div>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</body>
</html>
