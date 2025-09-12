<?php require_once 'classloader.php'; ?>

<?php 

if (!$userObj->isLoggedIn()) {
  header("Location: login.php");
}

if (!$userObj->isAdmin()) {
  header("Location: ../writer/index.php");
}  

?>


<!doctype html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>

  <!-- fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Anton&family=Nerko+One&family=Playfair+Display&display=swap" rel="stylesheet">

  <title>Viewing Articles</title>

  <style>
    body {
      font-family: "Times New Roman";
      margin-bottom: 2rem;
    }
  </style>

</head>
<body style="background-color: #FAF7F3;">

  <?php include 'includes/navbar.php'; ?>
  <div class="container-fluid">

    <div class="container-fluid text-center mt-4 py-2" style="background-color: #748DAE;">
      <h2 style="color: #F7F4EA; font-family: Nerko One;" class="cute-header">Dive into insightful reads with our latest articles! 
        <span style="font-size: 20px; margin-left: 5px;">˶ᵔ ᵕ ᵔ˶</span>
      </h2>
    </div>

    <!-- inserting/posting an article from the admin side. -->
    <div class="row justify-content-center">
      <div class="col-md-10">
      
        <h2 class="text-center mt-4"></h2>
        <?php $articles = $articleObj->getActiveArticles(); ?>
        <?php foreach ($articles as $article) { ?>

          <div class="card mt-4 shadow">
            <div class="card-body">
              
              <!-- displays the article details -->
              <h1 class="text-center" style="font-family: Anton, sans-serif;"><?= htmlspecialchars($article['title']) ?></h1>
              
              <?php if (!empty($article['image_url'])): ?>
                <img src="../<?= htmlspecialchars($article['image_url'], ENT_QUOTES, 'UTF-8') ?>" alt="Article Header" class="img-fluid mb-3" style="width: 100%; height: 200px; object-fit: cover;">
              <?php endif; ?>

              <small class="d-block text-center my-2">
                <strong class="text-success"><?= htmlspecialchars($article['username']) ?></strong> - <?= $article['created_at'] ?>
              </small>

              <?php if ($article['is_admin'] == 1): ?>
                <p class="text-center"><small class="bg-primary text-white p-2">Message From Admin</small></p>
              <?php endif; ?>

              <p class="text-justify" style="font-size: 15px; font-family: Georgia;">
                <?= nl2br(htmlspecialchars($article['content'])) ?>
              </p>

            </div>
            <div class="text-right mr-4 mb-4">
              <form action="core/handleForms.php" method="POST">
                  <input type="hidden" name="article_id" value="<?php echo $article['article_id']; ?>" />
                  <button type="submit" class="btn btn-danger w-24" name="deleteWriterPostedArticleBtn">
                      Delete Article
                  </button>
              </form>
            </div>

          </div>  

        <?php } ?> 
      </div>
    </div>

  </div>

</body>
</html>