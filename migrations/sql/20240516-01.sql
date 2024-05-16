ALTER TABLE posts
    MODIFY COLUMN text LONGTEXT
        CHARACTER SET utf8mb4
            COLLATE utf8mb4_0900_ai_ci;