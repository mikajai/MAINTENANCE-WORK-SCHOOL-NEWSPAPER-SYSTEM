<?php
require_once 'classloader.php';

$user_id = $_SESSION['user_id'] ?? null;
$hasNewNotifications = false;

if ($user_id) {
  $hasNewNotifications = $notificationObj->hasUnreadNotifications($user_id);
}
?>

<!-- fonts -->
<link href="https://fonts.googleapis.com/css2?family=Anton&family=Nerko+One&family=Playfair+Display&display=swap" rel="stylesheet">

<style>
  .notification-dot {
    position: absolute;
    top: 8px;
    right: 7px;
    width: 10px;
    height: 10px;
    background-color: red;
    border-radius: 50%;
    z-index: 10;
  }

  a {
    font-family: Nerko One, sans-serif;
    color: white;
  }
</style>

<nav class="navbar navbar-expand-lg navbar-dark p-3" style="background-color: #355E3B;">

  <img src="https://cdn-icons-png.flaticon.com/128/4075/4075591.png" alt="" style="margin-left: 35px; width: 24px; height: 24px; filter: brightness(0) invert(1);">
  <a class="navbar-brand ml-3" href="index.php" style="font-size: 28px;">Content Writers Panel</a>

  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" 
    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav ml-auto mr-4">

      <li class="nav-item">
        <a class="nav-link" href="index.php" style="font-size: 20px;">Home</a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="articles_submitted.php" style="font-size: 20px;">Articles Submitted</a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="shared_articles.php" style="font-size: 20px;">Shared Articles</a>
      </li>

      <!-- notification icon -->
      <li class="nav-item position-relative">
        <a class="nav-link" href="view_notifications.php">
          <img src="https://cdn-icons-png.flaticon.com/128/1827/1827347.png" alt="Notification Icon" style="width: 24px; height: 24px; filter: brightness(0) invert(1);">

          <?php if ($hasNewNotifications): ?>
            <span class="notification-dot"></span>
          <?php endif; ?>
        </a>
      </li>

      <!-- logout icon -->
      <li class="nav-item">
        <a class="nav-link" href="core/handleForms.php?logoutUserBtn=1">
          <img src="https://cdn-icons-png.flaticon.com/128/4400/4400629.png" alt="Logout Icon" style="width: 24px; height: 24px; filter: brightness(0) invert(1);">
        </a>
      </li>

    </ul>
  </div>
</nav>
