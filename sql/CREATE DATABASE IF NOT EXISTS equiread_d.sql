CREATE DATABASE IF NOT EXISTS equiread_db;
USE equiread_db;

CREATE TABLE IF NOT EXISTS tbl_users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    accessibility_profile VARCHAR(50) DEFAULT 'normal',
    role VARCHAR(20) DEFAULT 'user',
    profile_picture VARCHAR(255) DEFAULT 'default-avatar.png',
    bio TEXT,
    join_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS tbl_categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    color VARCHAR(7) DEFAULT '#ff85a2',
    icon VARCHAR(50) DEFAULT 'fas fa-folder',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS tbl_resources (
    resource_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    file_path VARCHAR(500),
    file_type VARCHAR(50),
    file_size INT DEFAULT 0,
    category VARCHAR(100),
    tags VARCHAR(255),
    upload_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    uploaded_by INT,
    downloads_count INT DEFAULT 0,
    is_featured BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (uploaded_by) REFERENCES tbl_users(user_id) ON DELETE SET NULL
);


CREATE TABLE IF NOT EXISTS tbl_downloads (
    download_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    resource_id INT,
    download_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES tbl_users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (resource_id) REFERENCES tbl_resources(resource_id) ON DELETE CASCADE
);


CREATE TABLE IF NOT EXISTS tbl_user_favorites (
    favorite_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    resource_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES tbl_users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (resource_id) REFERENCES tbl_resources(resource_id) ON DELETE CASCADE,
    UNIQUE KEY unique_favorite (user_id, resource_id)
);

INSERT IGNORE INTO tbl_users (full_name, email, password, role) 
VALUES ('Admin User', 'admin@equiread.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

	
INSERT IGNORE INTO tbl_categories (name, description, color, icon) VALUES
('Science', 'Scientific resources and materials', '#ff85a2', 'fas fa-flask'),
('Mathematics', 'Math tutorials and exercises', '#ffb6c1', 'fas fa-calculator'),
('Literature', 'Books and literary works', '#d291bc', 'fas fa-book'),
('History', 'Historical documents and resources', '#c8a2c8', 'fas fa-monument'),
('Technology', 'Tech tutorials and guides', '#ff6b97', 'fas fa-laptop-code'),
('Arts', 'Creative arts and design', '#ff9eb5', 'fas fa-palette');


INSERT IGNORE INTO tbl_resources (title, description, file_type, category, uploaded_by, downloads_count) VALUES
('Introduction to PHP', 'A comprehensive guide to PHP programming for beginners', 'pdf', 'Technology', 1, 15),
('Mathematics for Beginners', 'Basic mathematics concepts and exercises', 'pdf', 'Mathematics', 1, 23),
('World History Overview', 'Key events in world history from ancient to modern times', 'doc', 'History', 1, 8),
('Chemistry Basics', 'Fundamental concepts of chemistry with practical examples', 'pdf', 'Science', 1, 12),
('Creative Writing Guide', 'Tips and techniques for creative writing', 'docx', 'Literature', 1, 17);

-- Insert sample downloads
INSERT IGNORE INTO tbl_downloads (user_id, resource_id) VALUES
(1, 1), (1, 2), (1, 3), (1, 4), (1, 5);

CREATE INDEX idx_resources_category ON tbl_resources(category);
CREATE INDEX idx_resources_upload_date ON tbl_resources(upload_date);
CREATE INDEX idx_downloads_user_date ON tbl_downloads(user_id, download_date);
CREATE INDEX idx_downloads_date ON tbl_downloads(download_date);


ALTER TABLE tbl_users ADD COLUMN agreed_terms ENUM('yes','no') DEFAULT 'no';
-- OR with timestamp:
ALTER TABLE tbl_users ADD COLUMN terms_agreed_at TIMESTAMP NULL DEFAULT NULL;
