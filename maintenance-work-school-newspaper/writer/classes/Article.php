<?php  

require_once 'Database.php';
require_once 'User.php';
/**
 * Class for handling Article-related operations.
 * Inherits CRUD methods from the Database class.
 */
class Article extends Database {
    /**
     * Creates a new article.
     * @param string $title The article title.
     * @param string $image_url The url of the image.
     * @param string $content The article content.
     * @param int $author_id The ID of the author.
     * @return int The ID of the newly created article.
     */
    public function createArticle($title, $image_url, $content, $author_id) {
        $sql = "INSERT INTO articles (title, image_url, content, author_id, is_active) VALUES (?, ?, ?, ?, 1)";
        return $this->executeNonQuery($sql, [$title, $image_url, $content, $author_id]);
    }

    /**
     * Retrieves articles from the database.
     * @param int|null $id The article ID to retrieve, or null for all articles.
     * @return array
     */
    public function getArticles($id = null) {
        if ($id) {
            $sql = "SELECT * FROM articles WHERE article_id = ?";
            return $this->executeQuerySingle($sql, [$id]);
        }
        $sql = "SELECT * FROM articles 
                JOIN school_publication_users 
                ON articles.author_id = school_publication_users.user_id 
                ORDER BY articles.created_at DESC";
        return $this->executeQuery($sql);
    }

    public function getActiveArticles($id = null) {
        if ($id) {
            $sql = "SELECT * FROM articles WHERE article_id = ?";
            return $this->executeQuerySingle($sql, [$id]);
        }
        $sql = "SELECT * FROM articles 
                JOIN school_publication_users ON 
                articles.author_id = school_publication_users.user_id 
                WHERE is_active = 1 ORDER BY articles.created_at DESC";
                
        return $this->executeQuery($sql);
    }

    public function getArticlesByUserID($user_id) {
        $sql = "SELECT * FROM articles 
                JOIN school_publication_users ON 
                articles.author_id = school_publication_users.user_id
                WHERE author_id = ? ORDER BY articles.created_at DESC";
        return $this->executeQuery($sql, [$user_id]);
    }

    public function getAllSharedArticles ($user_id) {
        $sql = "SELECT articles.*, school_publication_users.username 
        FROM articles
        JOIN notification ON articles.article_id = notification.article_id
        JOIN school_publication_users ON articles.author_id = school_publication_users.user_id
        WHERE notification.receiver_id = ? 
        AND notification.notification_type = 'Edit Request Accepted'
        ORDER BY articles.created_at DESC";
            
        return $this->executeQuery($sql, [$user_id]);
    }

    /**
     * Updates an article.
     * @param int $id The article ID to update.
     * @param string $title The new title.
     * @param string $content The new content.
     * @return int The number of affected rows.
     */
    public function updateArticle($id, $title, $content) {
        $sql = "UPDATE articles SET title = ?, content = ? WHERE article_id = ?";
        return $this->executeNonQuery($sql, [$title, $content, $id]);
    }
    
    /**
     * Toggles the visibility (is_active status) of an article.
     * This operation is restricted to admin users only.
     * @param int $id The article ID to update.
     * @param bool $is_active The new visibility status.
     * @return int The number of affected rows.
     */
    public function updateArticleVisibility($id, $is_active) {
        $userModel = new User();
        if (!$userModel->isAdmin()) {
            return 0;
        }
        $sql = "UPDATE articles SET is_active = ? WHERE article_id = ?";
        return $this->executeNonQuery($sql, [(int)$is_active, $id]);
    }


    /**
     * Deletes an article.
     * @param int $id The article ID to delete.
     * @return int The number of affected rows.
     */
    public function deleteArticle($id) {
        $sql = "DELETE FROM articles WHERE article_id = ?";
        return $this->executeNonQuery($sql, [$id]);
    }
}
?>