<?php  
require_once 'classes/Notification.php';
require_once 'classes/Article.php';
require_once 'classes/Database.php';
require_once 'classes/User.php';

$databaseObj= new Database();
$userObj = new User();
$articleObj = new Article();
$notificationObj = new Notification();

$userObj->startSession();
?>