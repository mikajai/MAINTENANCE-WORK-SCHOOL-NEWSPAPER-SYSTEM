<?php  

require_once 'Database.php';
require_once 'Article.php';
require_once 'User.php';


class Notification extends Database {

    public const deletion_of_article = 'Deletion of Article';
    public const request_for_edit = 'Request for Edit';
    public const request_accepted = 'Edit Request Accepted';
    public const request_denied = 'Edit Request Denied';


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

    public function getEditRequestNotificationsByUser($user_id) {
        $sql = "SELECT * FROM notification WHERE receiver_id = ? AND notification_type = ?";
        return $this->executeQuery($sql, [$user_id, self::request_accepted]);
    }

    /**
     * Fetch all notifications for a given user.
     *
     * @param int $user_id
     * @return array
     */
    public function getNotificationsForUser($user_id) {
        $sql = "SELECT notification.*, 
               articles.title AS article_title, 
               school_publication_users.username AS sender_name 
               FROM notification
               LEFT JOIN articles ON notification.article_id = articles.article_id
               INNER JOIN school_publication_users ON notification.sender_id = school_publication_users.user_id
               WHERE notification.receiver_id = ?
               ORDER BY notification.created_at DESC";
        return $this->executeQuery($sql, [$user_id]);
    }


    public function getNotificationById($notification_id) {
        $sql = "SELECT * FROM notification WHERE notification_id = ?";
        $result = $this->executeQuery($sql, [$notification_id]);
        return $result ? $result[0] : null;
    }


    public function updateNotificationType($notification_id, $notification_type) {
        $sql = "UPDATE notification 
                SET notification_type = ? 
                WHERE notification_id = ?";
        return $this->executeNonQuery($sql, [$notification_type, $notification_id]);
    }


    /**
     * Marks a notification as read.
     *
     * @param int $notification_id
     * @return int Number of affected rows
     */
    public function markAsRead($notification_id) {
        $sql = "UPDATE notification SET is_read = 1 WHERE notification_id = ?";
        return $this->executeNonQuery($sql, [$notification_id]);
    }

    /**
     * Deletes a notification.
     *
     * @param int $notification_id
     * @return int Number of affected rows
     */
    public function deleteNotification($notification_id) {
        $sql = "DELETE FROM notification WHERE notification_id = ?";
        return $this->executeNonQuery($sql, [$notification_id]);
    }

    public function getNotificationsByArticle($article_id) {
        $sql = "SELECT notification.*, 
                    school_publication_users.username AS sender_name
                FROM notification
                INNER JOIN school_publication_users 
                    ON notification.sender_id = school_publication_users.user_id
                WHERE notification.article_id = ?
                ORDER BY notification.created_at DESC";
        return $this->executeQuery($sql, [$article_id]);
    }

    public function hasUnreadNotifications($user_id) {
    $sql = "SELECT COUNT(*) FROM notification WHERE receiver_id = ? AND is_read = 0";
    $count = $this->executeQuery($sql, [$user_id]);
    return $count[0]['COUNT(*)'] > 0;
}

}

?>