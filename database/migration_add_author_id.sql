-- Migration script to add missing columns for the updated schema
-- Run this to update existing database to match the fixed schema

USE nuck_blog;

-- Add author_id column to blogs table if it doesn't exist
ALTER TABLE blogs 
ADD COLUMN IF NOT EXISTS author_id int(11) DEFAULT NULL AFTER slug,
ADD COLUMN IF NOT EXISTS status enum('published','draft','archived') DEFAULT 'published' AFTER image_path6,
ADD COLUMN IF NOT EXISTS created_at timestamp NOT NULL DEFAULT current_timestamp() AFTER status,
ADD COLUMN IF NOT EXISTS updated_at timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() AFTER created_at;

-- Add index for author_id if it doesn't exist
ALTER TABLE blogs 
ADD INDEX IF NOT EXISTS idx_author_id (author_id),
ADD INDEX IF NOT EXISTS idx_category (category),
ADD INDEX IF NOT EXISTS idx_publish_date (publish_date),
ADD INDEX IF NOT EXISTS idx_status (status);

-- Add created_at and updated_at to admins table if they don't exist
ALTER TABLE admins
ADD COLUMN IF NOT EXISTS created_at timestamp NOT NULL DEFAULT current_timestamp() AFTER password,
ADD COLUMN IF NOT EXISTS updated_at timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() AFTER created_at;

-- Add foreign key constraint if it doesn't exist
ALTER TABLE blogs
ADD CONSTRAINT IF NOT EXISTS blogs_author_id_fk 
FOREIGN KEY (author_id) REFERENCES admins (id) ON DELETE SET NULL ON UPDATE CASCADE;

-- Update existing blogs to have admin user (id=1) as author
UPDATE blogs SET author_id = 1 WHERE author_id IS NULL;

-- Hash the admin password if it's still plain text
UPDATE admins 
SET password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' 
WHERE username = 'admin' AND password = 'admin123';

-- Clean up students table by removing test data
DELETE FROM students WHERE name_khmer = 'ណាង រដ្ឋា';

-- Add missing indexes to students table
ALTER TABLE students
ADD INDEX IF NOT EXISTS idx_payment_status (payment_status),
ADD INDEX IF NOT EXISTS idx_created_at (created_at);
