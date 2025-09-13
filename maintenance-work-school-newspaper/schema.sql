CREATE TABLE school_publication_users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    is_admin TINYINT(1) NOT NULL DEFAULT 0,
    date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE articles (
    article_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    image_url VARCHAR(2083),
    content TEXT NOT NULL,
    author_id INT NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES school_publication_users(user_id)
);


CREATE TABLE notification (
    notification_id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,         
    receiver_id INT NOT NULL,       
    article_id INT,       
    notification_type ENUM('Deletion of Article', 'Request for Edit', 'Edit Request Accepted', 'Edit Request Denied') NOT NULL,
    message TEXT, 
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES school_publication_users(user_id),
    FOREIGN KEY (receiver_id) REFERENCES school_publication_users(user_id),
    FOREIGN KEY (article_id) REFERENCES articles(article_id)

);
