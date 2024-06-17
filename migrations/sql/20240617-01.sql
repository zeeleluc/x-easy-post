CREATE TABLE auth_identifiers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    auth_identifier TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
