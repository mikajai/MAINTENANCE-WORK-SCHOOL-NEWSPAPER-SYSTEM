<?php 
require_once 'writer/classloader.php'; 
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

  <style>
    body {
      font-family: "Garamond";
    }
  </style>
</head>
<body style="background-color: #A8BBA3;">

  <!-- navigation bar -->
  <nav class="navbar navbar-expand-lg p-3" style="background-color: #FFFF;">
    <div class="container-fluid d-flex justify-content-center align-items-center">
      <img src="https://cdn-icons-png.flaticon.com/128/3208/3208892.png" style="width: auto; height: 50px; margin-right: 10px;">
      <a class="navbar-brand" href="index.php" style="color: black; font-family: Nerko One, sans-serif; font-size: 35px; letter-spacing: 2px; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);"><b>Welcome to the School Publication Homepage</b></a>
    </div>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
  </nav>

  <div class="container-fluid my-4">
    <div class="row">

      <!-- admin description -->
      <div class="col-md-6">
        <div class="card shadow-lg border rounded">
          <div class="card-body">
            <h3 style="text-align: center; font-family: Nerko One; border-bottom: 2px solid #9A3F3F; padding-bottom: 10px;">
              Wondering what an <b style="color: #9A3F3F;">Admin writers</b> do?
            </h3>
            <div class="d-flex align-items-center justify-content-between mt-3">
              <img src="https://png.pngtree.com/png-vector/20240318/ourmid/pngtree-cute-chibi-girl-student-in-school-uniform-while-attending-online-class-png-image_12006290.png" class="img-fluid rounded-circle shadow-sm" style="width: 150px; height: 150px; border: 3px solid #A8BBA3;">
              <p class="p-3">Admin writers play a key role in content team development. They are the highest-ranking editorial authority responsible for managing the entire editorial process, and aligning all published material with the publication’s overall vision and strategy.</p>
            </div>
          </div>
        </div>
      </div>

      <!-- writer description -->
      <div class="col-md-6">
        <div class="card shadow-lg border rounded">
          <div class="card-body">
             <h3 style="text-align: center; font-family: Nerko One; border-bottom: 2px solid #9A3F3F; padding-bottom: 10px;">
              Wondering what a <b style="color: #9A3F3F;">Content writers</b> do?
            </h3>
            <div class="d-flex align-items-center justify-content-between mt-3">
              <img src="https://ih1.redbubble.net/image.5237225095.7613/st,extra_large,507x507-pad,600x600,f8f8f8.u3.jpg" class="img-fluid rounded-circle shadow-sm" style="width: 150px; height: 150px; border: 3px solid #A8BBA3;">
              <p class="p-3">Content writers create clear, engaging, and informative content that helps businesses communicate their services or products effectively, build brand authority, attract and retain customers, and drive web traffic and conversions.</p>
            </div>
          </div>
        </div>
      </div>

    </div>

    <div class="container-fluid text-center mt-4 py-2" style="background-color: #C1856D;">
      <h2 style="color: #F7F4EA; font-family: Nerko One;" class="cute-header">Dive into insightful reads with our latest articles! 
        <span style="font-size: 20px; margin-left: 5px;">˶ᵔ ᵕ ᵔ˶</span>
      </h2>
    </div>

    <div class="row justify-content-center">
      <div class="col-md-10">
        <?php $articles = $articleObj->getActiveArticles(); ?>

        <!-- checker if there are articles to show -->
        <?php if (empty($articles)): ?>
          <div class="alert alert-warning text-center mt-5">
            <strong>No articles to show as of now.</strong>
          </div>
        <?php else: ?>

          <?php foreach ($articles as $article) { ?>
            <div class="card mt-4 shadow">
              <div class="card-body">

                <h1 style="text-align: center; font-family: Anton, sans-serif;"><?php echo $article['title']; ?></h1>
                <small style="display: block; text-align: center; margin-top: 10px; margin-bottom: 10px;"><strong style="color: green;"><?php echo $article['username']; ?></strong> - <?php echo $article['created_at']; ?> </small>

                <?php if (!empty($article['image_url'])): ?>
                  <img src="<?= htmlspecialchars($article['image_url'], ENT_QUOTES, 'UTF-8') ?>" alt="Article Header" class="img-fluid mb-3" style="width: 100%; height: 350px; object-fit: cover;">
                <?php endif; ?>

                
                <?php if ($article['is_admin'] == 1) { ?>
                  <p style="text-align: center;"><small class="bg-primary text-white p-2">  
                    Message From Admin
                  </small></p>
                <?php } ?>

                <p class="text-justify" style="font-size: 15px; font-family: Georgia;"><?= nl2br(htmlspecialchars($article['content'])) ?> </p>

              </div>
            </div>  
          <?php } ?>

        <?php endif; ?>
      </div>
    </div>
  </div>
</body>
</html>
