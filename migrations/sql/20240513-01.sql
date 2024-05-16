CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id VARCHAR(255),
    success BOOLEAN,
    image TEXT,
    image_type VARCHAR(255) NOT NULL,
    readable_result VARCHAR(255) NOT NULL,
    result JSON NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
