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
  
  <title>Pending Articles</title>

  <style>
    body { 
      font-family: "Garamond";
    }
  </style>

</head>
<body style="background-color: #FAF7F3;">
  <?php include 'includes/navbar.php'; ?>
    <div class="container-fluid my-4">
      <div class="row justify-content-center">
        <div class="col-md-10">
          <h3 style="font-family: Nerko One; text-align: center; color: #465C88;">Pending Articles for Review</h3>

          <?php $articles = $articleObj->getArticles(); ?>

          <?php foreach ($articles as $article) { ?>
            <div class="card mt-4 shadow articleCard">
              <div class="card-body">

                <!-- displays the article details -->
                <h1 class="text-center" style="font-family: Anton, sans-serif;"><?php echo $article['title']; ?></h1> 
                
                <?php if (!empty($article['image_url'])): ?>
                  <img src="../<?= htmlspecialchars($article['image_url']) ?>" alt="Article Header" class="img-fluid mb-3" style="width: 100%; height: 350px; object-fit: cover;" />
                <?php endif; ?>

                <small class="d-block text-center my-1">
                  <strong class="text-success"><?= htmlspecialchars($article['username']) ?></strong> - <?= $article['created_at'] ?>
                </small>
                
                <div class="text-center">
                  <?php if ($article['is_active'] == 0) { ?>
                    <b><p class="text-danger">Status: PENDING</p></b>
                  <?php } ?>
                  <?php if ($article['is_active'] == 1) { ?>
                    <b><p class="text-success">Status: ACTIVE</p></b>
                  <?php } ?>
                </div>

                <p class="text-justify" style="font-size: 15px; font-family: Georgia;">
                  <?= nl2br(htmlspecialchars($article['content'])) ?>
                </p>
                
                <form class="deleteArticleForm">
                  <input type="hidden" name="article_id" value="<?php echo $article['article_id']; ?>" class="article_id">
                  <input type="submit" class="btn btn-danger float-right mb-4 deleteArticleBtn" value="Delete">
                </form>

                <form class="updateArticleStatus">
                  <input type="hidden" name="article_id" value="<?php echo $article['article_id']; ?>" class="article_id">
                  <select name="is_active" class="form-control is_active_select" article_id=<?php echo $article['article_id']; ?>>
                    <option value="">Select an option</option>
                    <option value="0">Pending</option>
                    <option value="1">Active</option>
                  </select>
                </form>
                
                <div class="updateArticleForm d-none">
                  <h4>Edit the article</h4>
                  <form action="core/handleForms.php" method="POST">
                    <div class="form-group mt-4">
                      <input type="text" class="form-control" name="title" value="<?php echo $article['title']; ?>">
                    </div>
                    <div class="form-group">
                      <textarea name="description" id="" class="form-control"><?php echo $article['content']; ?></textarea>
                      <input type="hidden" name="article_id" value="<?php echo $article['article_id']; ?>">
                      <input type="submit" class="btn btn-primary float-right mt-4" name="editArticleBtn">
                    </div>
                  </form>
                </div>
              </div>
            </div>  
          <?php } ?> 
        </div>
      </div>
    </div>
  <script>
    // $('.articleCard').on('dblclick', function (event) {
    //   var updateArticleForm = $(this).find('.updateArticleForm');
    //   updateArticleForm.toggleClass('d-none');
    // });

    // $('.deleteArticleForm').on('submit', function (event) {
    //   event.preventDefault();
    //   var formData = {
    //     article_id: $(this).find('.article_id').val(),
    //     deleteArticleBtn: 1
    //   }
    //   if (confirm("Are you sure you want to delete this article?")) {
    //     $.ajax({
    //       type:"POST",
    //       url: "core/handleForms.php",
    //       data:formData,
    //       success: function (data) {
    //         if (data) {
    //           location.reload();
    //         }
    //         else{
    //           alert("Deletion failed");
    //         }
    //       }
    //     })
    //   }
    // })

    $('.is_active_select').on('change', function (event) {
      event.preventDefault();
      var formData = {
        article_id: $(this).attr('article_id'),
        status: $(this).val(),
        updateArticleVisibility:1
      }

      if (formData.article_id != "" && formData.status != "") {
        $.ajax({
          type:"POST",
          url: "core/handleForms.php",
          data:formData,
          success: function (data) {
            if (data) {
              location.reload();
            }
            else{
              alert("Visibility update failed");
            }
          }
        })
      }
    })
  </script>
</body>
</html>