<?php require_once 'classloader.php'; ?>

<?php 
if (!$userObj->isLoggedIn()) {
  header("Location: login.php");
}

if ($userObj->isAdmin()) {
  header("Location: ../admin/index.php");
}  


$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

$notifications = $notificationObj->getNotificationsForUser($user_id);

foreach ($notifications as $notification) {
  if ($notification['is_read'] == 0) {
      $notificationObj->markAsRead($notification['notification_id']);
  }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>

   <!-- fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Anton&family=Nerko+One&family=Playfair+Display&display=swap" rel="stylesheet">

  <title>Your Notifications</title>
  <style>
      body {
        background-color: #f8f9fa;
        font-family: 'Garamond'
      }
      .notification-card {
        cursor: default;
      }
      .notification-card.unread {
        background-color: #e9f7ef;
        border-left: 5px solid #28a745;
      }
      .notification-message {
        white-space: pre-line;
      }
      .no-notifications {
        font-family: Nerko One;
        font-size: 25px;
        font-style: normal; 
        color: black; 
      }
  </style>
</head>
<body style="background-color: #CADCAE;">
  <?php include 'includes/navbar.php'; ?>

  <div class="container my-5">

    <?php if (empty($notifications)): ?>
      <p class="no-notifications">You have no notifications at this time.</p>
    <?php else: ?>
      <div class="notifList">
        <?php foreach ($notifications as $notification): ?>
          <div class="list-group-item notification-card <?= $notification['is_read'] == 0 ? 'unread' : '' ?>">
            <div class="d-flex w-100 justify-content-between">
              <h5 class="mb-1">
                <?= htmlspecialchars($notification['sender_name']) ?>
                <small class="text-muted"> - <?= date('M d, Y H:i', strtotime($notification['created_at'])) ?></small>
              </h5>
            </div>
            <p class="mb-1 notification-message"><?= htmlspecialchars($notification['message']) ?></p>
            <form action="core/handleForms.php" method="POST">
              <div class="d-flex justify-content-end">
                  <input type="hidden" name="notification_id" value="<?= $notification['notification_id']; ?>" />
                  <button type="submit" name="deleteNotificationBtn" class="btn btn-sm btn-danger">Delete</button>
              </div>
            </form>


            <?php if ($notification['notification_type'] === 'Request for Edit' && !$notification['is_read']): ?>
              <!-- Accept / Reject buttons example -->
              <form method="POST" action="core/handleForms.php" class="mt-2">
                <input type="hidden" name="notification_id" value="<?= $notification['notification_id'] ?>">
                <input type="hidden" name="article_id" value="<?= $notification['article_id'] ?>">
                <button type="submit" name="acceptEditRequest" class="btn btn-sm btn-success mr-2">Accept Request</button>
                <button type="submit" name="rejectEditRequest" class="btn btn-sm btn-danger">Reject Request</button>
              </form>
            <?php endif; ?>

          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</body>
</html>
