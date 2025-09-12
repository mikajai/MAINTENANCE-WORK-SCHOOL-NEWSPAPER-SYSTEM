<?php 
require_once 'classloader.php'; 

if (!$userObj->isLoggedIn()) {
  header("Location: login.php");
}

if (!$userObj->isAdmin()) {
  header("Location: ../writer/index.php");
}  


$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
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

  <title>Admin Dashboard</title>

  <style>
    body { 
      font-family: "Garamond";
    }
  </style>

</head>
<body style="background-color: #FAF7F3;">

<?php include 'includes/navbar.php'; ?>
  <div class="container-fluid my-4">

    <!-- inserting/posting an article from an admin writer -->
    <div class="row justify-content-center">
      <div class="col-md-8">
        
        <div class="p-4 mt-3 mb-4" style="background-color: #ffffffff; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);">
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

          <form action="core/handleForms.php" method="POST" enctype="multipart/form-data">

            <!-- Upload image -->
            <div class="form-group">
              <label for="image">Header Image</label>
              <input type="file" class="form-control h-auto" name="image" accept="image/*">
            </div>

            <div class="form-group">
              <input type="text" class="form-control" name="title" placeholder="Input title here" required>
            </div>

            <div class="form-group">
              <textarea name="description" class="form-control" placeholder="Message as admin" rows="5" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary mt-3 w-50 d-block mx-auto" name="insertAdminArticleBtn">
              Submit
            </button>
          </form>
        </div>
        
      </div>
    </div>

  </div>

</body>
</html>