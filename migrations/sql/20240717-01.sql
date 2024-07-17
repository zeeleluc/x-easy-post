CREATE TABLE images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    uuid VARCHAR(255) NOT NULL,
    created_by TEXT NOT NULL,
    project VARCHAR(255) NOT NULL,
    text_image VARCHAR(255),
    nft_id VARCHAR(255),
    nft_type VARCHAR(255),
    url TEXT NOT NULL,
    can_redo TINYINT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
