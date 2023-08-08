CREATE DATABASE IF NOT EXISTS db_manager_stores;
USE db_manager_stores;
-- Tạo bảng Users
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    full_name VARCHAR(100),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tạo bảng Stores
CREATE TABLE stores (
    store_id INT AUTO_INCREMENT PRIMARY KEY,
    store_name VARCHAR(100) NOT NULL,
    user_id INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- Tạo bảng Products
CREATE TABLE products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(100) NOT NULL,
    store_id INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    description TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (store_id) REFERENCES stores(store_id)
);

-- Tạo bảng Sessions
CREATE TABLE sessions (
    session_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    user_agent TEXT,
    token VARCHAR(64),
    refresh_token VARCHAR(64),
    token_expires_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    refresh_token_expires_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- Tạo bảng User_Store_Connection
CREATE TABLE user_store_connection (
    user_store_connection_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    store_id INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (store_id) REFERENCES stores(store_id)
);

INSERT INTO users (username, password, email, full_name, created_at, updated_at)
VALUES
    ('john123', 'hashed_password_1', 'john123@example.com', 'John Doe', NOW(), NOW()),
    ('sarah89', 'hashed_password_2', 'sarah89@example.com', 'Sarah Smith', NOW(), NOW()),
    ('mike22', 'hashed_password_3', 'mike22@example.com', 'Mike Johnson', NOW(), NOW()),
    ('emma_k', 'hashed_password_4', 'emma.k@example.com', 'Emma Kent', NOW(), NOW()),
    ('alex87', 'hashed_password_5', 'alex87@example.com', 'Alex Brown', NOW(), NOW());

INSERT INTO stores (store_name, user_id, created_at, updated_at)
VALUES
    ('Electronics Store', 1, NOW(), NOW()),
    ('Fashion Boutique', 2, NOW(), NOW()),
    ('Bookstore', 3, NOW(), NOW()),
    ('Sports Gear Shop', 4, NOW(), NOW()),
    ('Home Decor Store', 5, NOW(), NOW());

INSERT INTO products (product_name, store_id, price, description, created_at, updated_at)
VALUES
    ('Laptop', 1, 800, 'Powerful laptop with high-end specifications', NOW(), NOW()),
    ('Smartphone', 1, 500, 'Feature-rich smartphone with great camera', NOW(), NOW()),
    ('Smart Watch', 2, 150, 'Stylish smartwatch with fitness tracking', NOW(), NOW()),
    ('Headphones', 3, 100, 'Noise-canceling headphones with great sound', NOW(), NOW()),
    ('Wireless Earbuds', 4, 80, 'True wireless earbuds with long battery life', NOW(), NOW());

INSERT INTO sessions (user_id, ip_address, user_agent, token, refresh_token, token_expires_at, refresh_token_expires_at, created_at, updated_at)
VALUES
    (1, '192.168.1.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36 Edge/16.16299', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoxLCJ1c2VybmFtZSI6ImpvaG5fMTIzIiwiZW1haWwiOiJqb2huMTIzQGV4YW1wbGUuY29tIiwiaWF0IjoxNjI4MjE4MzQ0LCJleHAiOjE2MjgyMjE5NDR9.VY-wJbPsU4eAQ8_1IJTik75HnVEK5EioMknPtDsHWhA', 'rN1m1vyB3yAQp9rWZSd5HjJzN0jEanqddzZxLxwsdz4', NOW(), NOW() + INTERVAL 7 DAY, NOW(), NOW()),
    (3, '192.168.1.101', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36 Edge/16.16299', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjozLCJ1c2VybmFtZSI6Im1pa2UyMiIsImVtYWlsIjoibWlrZTIyQGV4YW1wbGUuY29tIiwiaWF0IjoxNjI4MjE4MzQ0LCJleHAiOjE2MjgyMjE5NDR9.Z8V_5xWVGSC5fHARShSWbkIVxqgu30o3IYa6kNk0D28', 'X08QG4NVvXccYKqKrZgDnIh1G7RKFLJ', NOW(), NOW() + INTERVAL 7 DAY, NOW(), NOW());

INSERT INTO user_store_connection (user_id, store_id, created_at)
VALUES
    (1, 1, NOW()),
    (2, 2, NOW()),
    (3, 3, NOW()),
    (4, 4, NOW()),
    (5, 5, NOW());
