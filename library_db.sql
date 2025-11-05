-- Database: library_db
CREATE DATABASE IF NOT EXISTS library_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE library_db;

-- Users table
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  email VARCHAR(100) NOT NULL UNIQUE,
  student_id VARCHAR(30) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('student','admin') NOT NULL DEFAULT 'student',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Books table
CREATE TABLE IF NOT EXISTS books (
  book_id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(200) NOT NULL,
  author VARCHAR(120) NOT NULL,
  category VARCHAR(80) NOT NULL,
  status ENUM('available','borrowed') NOT NULL DEFAULT 'available',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Borrowed books table
CREATE TABLE IF NOT EXISTS borrowed_books (
  id INT AUTO_INCREMENT PRIMARY KEY,
  book_id INT NOT NULL,
  user_id INT NOT NULL,
  borrowed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  returned_at TIMESTAMP NULL,
  CONSTRAINT fk_borrow_book FOREIGN KEY (book_id) REFERENCES books(book_id) ON DELETE CASCADE,
  CONSTRAINT fk_borrow_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Sample data
INSERT INTO users (username,email,student_id,password_hash,role) VALUES
  ('admin','admin@rpkarongi.ac.rw','ADM001', '$2y$10$0R9cFz0b4m7w.Pb1lP3QDe1m7m6s8N3oE2e7VQ2T7bQwN8aEo0mGq','admin')
ON DUPLICATE KEY UPDATE email=VALUES(email);

INSERT INTO books (title,author,category,status) VALUES
  ('Introduction to Algorithms','Cormen','CS','available'),
  ('Database System Concepts','Silberschatz','CS','available'),
  ('Clean Code','Robert C. Martin','Software','available');
