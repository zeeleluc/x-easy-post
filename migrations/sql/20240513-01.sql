CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id VARCHAR(255),
    posted BOOLEAN NOT NULL,
    image TEXT NOT NULL,
    reply_type VARCHAR(255) NOT NULL,
    readable_result VARCHAR(255) NOT NULL,
    result JSON NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
