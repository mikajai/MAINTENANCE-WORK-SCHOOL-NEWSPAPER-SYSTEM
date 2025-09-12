<?php  

require_once 'Database.php';
require_once 'Article.php';
require_once 'User.php';

class Notification extends Database {

    public const deletion_of_article = 'Deletion of Article';

    /**
     * Sends a notification to another user.
     * @param int $sender_id 
     * @param int $receiver_id 
     * @param int|null $article_id 
     * @param string $type 
     * @param string $message
     * @return bool
     */
    public function sendNotificationToAuthor($sender_id, $receiver_id, $article_id, $type, $message) {
        $sql = "INSERT INTO notification (sender_id, receiver_id, article_id, notification_type, message)
                VALUES (?, ?, ?, ?, ?)";
        return $this->executeNonQuery($sql, [$sender_id, $receiver_id, $article_id, $type, $message]);
    }
}
?>