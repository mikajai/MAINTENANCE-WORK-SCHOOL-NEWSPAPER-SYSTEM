<?php session_start(); ?>

<!doctype html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>

  <style>
    body {
      font-family: "Georgia";
      background-image: url('https://wallpapers.com/images/hd/writing-pictures-1920-x-1080-ft3ucpba6hasgzwh.jpg');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      background-attachment: fixed;
      min-height: 100vh;
    }
  </style>
  <title>Login - Writers Dashboard</title>
</head>
<body>
  <div class="container" style="min-height: 100vh;">
  <div class="row justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="col-md-8 p-1">
      <div class="card shadow p-2">
        <div class="card-header text-center" style="background-color: #355E3B; color: white;">
          <h2><b>Welcome to Writers Dashboard</b></h2>
          <p class="mb-0">Login Now!</p>
        </div>
        <form action="core/handleForms.php" method="POST">
          <div class="card-body">
            <?php  
              if (isset($_SESSION['message']) && isset($_SESSION['status'])) {

                if ($_SESSION['status'] == "200") {
                  echo "<h6 class='text-center' style='color: green; font-size: 18px;'>{$_SESSION['message']}</h1>";
                }

                else {
                  echo "<h6 class='text-center' style='color: red; font-size: 18px;'>{$_SESSION['message']}</h1>"; 
                }

              }
              unset($_SESSION['message']);
              unset($_SESSION['status']);
            ?>
            <div class="form-group">
              <label for="exampleInputEmail1">Email</label>
              <input type="email" class="form-control" name="email" required>
            </div>
            <div class="form-group">
              <label for="exampleInputPassword1">Password</label>
              <input type="password" class="form-control" name="password" required>
            </div>
            <input type="submit" class="btn btn-primary float-right mt-2 mb-2" name="loginUserBtn" value="Login">
            <p class="mt-2 mb-0">Don't have an account yet? <a href="register.php">Register here</a>.</p>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
</body>
</html>
