-- Simple migration script to add missing author_id column
USE nuck_blog;

-- Add author_id column to blogs table
ALTER TABLE blogs ADD COLUMN author_id int(11) DEFAULT NULL AFTER slug;

-- Add status column to blogs table  
ALTER TABLE blogs ADD COLUMN status enum('published','draft','archived') DEFAULT 'published' AFTER image_path6;

-- Add timestamp columns
ALTER TABLE blogs ADD COLUMN created_at timestamp NOT NULL DEFAULT current_timestamp() AFTER status;
ALTER TABLE blogs ADD COLUMN updated_at timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() AFTER created_at;

-- Add timestamp columns to admins
ALTER TABLE admins ADD COLUMN created_at timestamp NOT NULL DEFAULT current_timestamp() AFTER password;
ALTER TABLE admins ADD COLUMN updated_at timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() AFTER created_at;

-- Add indexes
ALTER TABLE blogs ADD INDEX idx_author_id (author_id);
ALTER TABLE blogs ADD INDEX idx_category (category);
ALTER TABLE blogs ADD INDEX idx_publish_date (publish_date);
ALTER TABLE blogs ADD INDEX idx_status (status);

-- Update existing blogs to have admin user as author
UPDATE blogs SET author_id = 1 WHERE author_id IS NULL;

-- Hash the admin password
UPDATE admins SET password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' WHERE username = 'admin';

-- Clean up test data
DELETE FROM students WHERE name_khmer = 'ណាង រដ្ឋា';
