<?php  
require_once '../classloader.php';


if (isset($_POST['insertNewUserBtn'])) {
	$username = htmlspecialchars(trim($_POST['username']));
	$email = htmlspecialchars(trim($_POST['email']));
	$password = trim($_POST['password']);
	$confirm_password = trim($_POST['confirm_password']);

	if (!empty($username) && !empty($email) && !empty($password) && !empty($confirm_password)) {

		if ($password == $confirm_password) {

			if (!$userObj->usernameExists($username)) {

				if ($userObj->registerUser($username, $email, $password)) {
					header("Location: ../login.php");
				}

				else {
					$_SESSION['message'] = "An error occured with the query!";
					$_SESSION['status'] = '400';
					header("Location: ../register.php");
				}
			}

			else {
				$_SESSION['message'] = $username . " as username is already taken";
				$_SESSION['status'] = '400';
				header("Location: ../register.php");
			}
		}
		else {
			$_SESSION['message'] = "Please make sure both passwords are equal";
			$_SESSION['status'] = '400';
			header("Location: ../register.php");
		}
	}
	else {
		$_SESSION['message'] = "Please make sure there are no empty input fields";
		$_SESSION['status'] = '400';
		header("Location: ../register.php");
	}
}

if (isset($_POST['loginUserBtn'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {

        if ($userObj->loginUser($email, $password)) {
            header("Location: ../index.php");
        } else {
            $_SESSION['message'] = "Username/password invalid. You must be a writer before proceeding.";
            $_SESSION['status'] = "400";
            header("Location: ../login.php");
        }
    } else {
        $_SESSION['message'] = "Please make sure there are no empty input fields";
        $_SESSION['status'] = '400';
        header("Location: ../login.php");
    }
}



if (isset($_GET['logoutUserBtn'])) {
	$userObj->logout();
	header("Location: ../index.php");
}


// added inserting an image when an admin creates an article
if (isset($_POST['insertArticleBtn'])) {
    $title = $_POST['title'];
    $content = $_POST['description'];
    $author_id = $_SESSION['user_id'];
    $image_url = null;

    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $uploadDir = '../../uploads/'; 
        if (!is_dir($uploadDir)) {
            $uploadDir = '../uploads/';
        }

        $filename = time() . '_' . basename($_FILES['image']['name']);
        $targetPath = $uploadDir . $filename;

       if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            $image_url = 'uploads/' . $filename; 
        }else {
            $_SESSION['message'] = "Failed to upload the image.";
            $_SESSION['status'] = "400";
            header("Location: ../index.php");
            exit();
        }
    }

    if($articleObj->createArticle($title, $image_url, $content, $author_id)){
		 $_SESSION['message'] = "Article posted successfully. Check it below!";
        $_SESSION['status'] = "200";
    } else {
        $_SESSION['message'] = "Failed to post the article.";
        $_SESSION['status'] = "400";
	}
    header("Location: ../index.php");
    exit();
}


// kept it as it is, posted/attached images cannot be edited unless the writer deletes and post another article
if (isset($_POST['editArticleBtn'])) {
	$title = $_POST['title'];
	$description = $_POST['description'];
	$article_id = $_POST['article_id'];
	if ($articleObj->updateArticle($article_id, $title, $description)) {
		header("Location: ../articles_submitted.php");
	}
}

// editing shared articles
if (isset($_POST['editSharedArticleButton'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $article_id = $_POST['article_id'];


    if ($articleObj->updateArticle($article_id, $title, $description)) {
        header("Location: ../shared_articles.php");
        exit();
    } else {
        echo "Failed to update the article. Please try again.";
    }
}

// saving edited shared article button
if (isset($_POST['saveArticleBtn'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $article_id = $_POST['article_id'];

    if ($articleObj->updateArticle($article_id, $title, $description)) {
        header("Location: ../shared_articles.php");
        exit();
    } else {
        echo "Failed to update the article. Please try again.";
    }
}


if (isset($_POST['deleteArticleBtn'])) {
	$article_id = $_POST['article_id'];
	echo $articleObj->deleteArticle($article_id);
}

// buttons for requesting an edit to the author of the article
if (isset($_POST['requestEditButton'])) {
    $sender_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    $article_id = $_POST['article_id'];

    $article = $articleObj->getArticles($article_id);

    if (!$article) {
        $_SESSION['error'] = "Article not found.";
        header("Location: ../index.php");
        exit;
    }

    $message = "{$username} has requested edit access to your article entitled '{$article['title']}'.";

    $notificationObj->sendNotificationToAuthor(
        $sender_id,
        $article['author_id'],
        $article_id,
        Notification::request_for_edit,
        $message
    );

    $_SESSION['success'] = "Edit request sent to the article author.";
    header("Location: ../index.php");
    exit;
}


// accept and delete button on the article author side once the request is sent
if (isset($_POST['acceptEditRequest']) || isset($_POST['rejectEditRequest'])) {
    $notification_id = $_POST['notification_id'];
    $article_id = $_POST['article_id'];

    $notification = $notificationObj->getNotificationById($notification_id);
    if (!$notification) {
        echo "Notification not found.";
    }

    // Fetch article info
    $article = $articleObj->getArticles($article_id);
    if (!$article) {
        echo "Article not found.";
    }

    $article_title = $article['title'];

    $sender_id = $notification['sender_id'];
    $receiver_id = $_SESSION['user_id']; // The current user processing the request
    $status = isset($_POST['acceptEditRequest']) ? 'accepted' : 'rejected';
    $type = $status === 'accepted' ? Notification::request_accepted : Notification::request_denied;

    $message = "Your edit request for the article titled '{$article_title}' has been {$status}. Check your Shared Articles.";

    // update the notification type and mark the notification as read
    $notificationObj->updateNotificationType($notification_id, $type);
    $notificationObj->markAsRead($notification_id);

    // send the notification to the sender of the request
    $notificationObj->sendNotificationToAuthor(
        $receiver_id,
        $sender_id,
        $article_id,
        $type,
        $message
    );

    header("Location: ../index.php");
    exit;
}

// delete the notification 
if (isset($_POST['deleteNotificationBtn'])) {
	$notification_id = $_POST['notification_id'];
	echo $notificationObj->deleteNotification($notification_id);
    header("Location: ../view_notifications.php");
}
