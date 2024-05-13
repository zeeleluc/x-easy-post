CREATE TABLE tweets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tweet_id VARCHAR(255) NOT NULL,
    posted BOOLEAN NOT NULL,
    reply_type VARCHAR(255) NOT NULL,
    result JSON NOT NULL,
    created_at DATETIME NOT NULL
);
