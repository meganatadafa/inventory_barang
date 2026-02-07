-- Add role column to login table
ALTER TABLE login ADD COLUMN role ENUM('admin', 'user') NOT NULL DEFAULT 'admin';

-- Update existing users to admin role
UPDATE login SET role = 'admin' WHERE role IS NULL OR role = '';