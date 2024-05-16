CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id VARCHAR(255),
    success BOOLEAN,
    `text` TEXT,
    image TEXT,
    image_type VARCHAR(255),
    readable_result VARCHAR(255),
    result JSON,
    posted_at TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
