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
		}
		else {
			$_SESSION['message'] = "Username/password invalid";
			$_SESSION['status'] = "400";
			header("Location: ../login.php");
		}
	}

	else {
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
if (isset($_POST['insertAdminArticleBtn'])) {
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
		 $_SESSION['message'] = "Article posted successfully. Check in view all articles!";
        $_SESSION['status'] = "200";
    } else {
        $_SESSION['message'] = "Failed to post the article.";
        $_SESSION['status'] = "400";
	}
    header("Location: ../index.php");
    exit();
}


// kept it as it is, posted/attached images cannot be edited unless the admin deletes and post another article
if (isset($_POST['editArticleBtn'])) {
	$title = $_POST['title'];
	$description = $_POST['description'];
	$article_id = $_POST['article_id'];
	if ($articleObj->updateArticle($article_id, $title, $description)) {
		header("Location: ../articles_submitted.php");
	}
}


if (isset($_POST['deleteArticleBtn'])) {
	$article_id = $_POST['article_id'];
	echo $articleObj->deleteArticle($article_id);
}


// deleting writer post then sends a notification to the article's author
if (isset($_POST['deleteWriterPostedArticleBtn'])) {
    $article_id = $_POST['article_id'];
    $article = $articleObj->getArticles($article_id);

    if ($article) {
        $article_title = $article['title'];
        $username = $_SESSION['username']; 
        $receiver_id = $article['author_id']; 
        $sender_id = $_SESSION['user_id'];


        $message = "Admin {$username} has deleted your article titled '{$article['title']}'.";
        $notifyWriter = $notificationObj->sendNotificationToAuthor($sender_id, $receiver_id, $article_id, Notification::deletion_of_article, $message);


        if ($notifyWriter) {
            $articleDeleted = $articleObj->deleteArticle($article_id);

            if ($articleDeleted) {
                $_SESSION['message'] = "Article successfully deleted and the author has been notified.";
                $_SESSION['status'] = '200'; 
                header("Location: ../view_all_articles.php");
                exit();
            } else {
                $_SESSION['message'] = "Failed to delete the article. Please try again.";
                $_SESSION['status'] = '400';
                header("Location: ../view_all_articles.php");
                exit();
            }

        } else {
            $_SESSION['message'] = "Failed to send notification to the author.";
            $_SESSION['status'] = '400';
            header("Location: ../view_all_articles.php");
            exit();
        }
    } else {
        $_SESSION['message'] = "The article could not be found.";
        $_SESSION['status'] = '400'; // Error status code
        header("Location: ../view_all_articles.php");
        exit();
    }
}


if (isset($_POST['updateArticleVisibility'])) {
	$article_id = $_POST['article_id'];
	$status = $_POST['status'];
	echo $articleObj->updateArticleVisibility($article_id,$status);
}