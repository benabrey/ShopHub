-- Add admin role to users table
ALTER TABLE users ADD COLUMN is_admin TINYINT(1) DEFAULT 0 AFTER email;

-- Making myself admin
UPDATE users SET is_admin = 1 WHERE email = 'benabrey1417@gmail.com';

-- Password is "adminpassword"
UPDATE users SET is_admin = 1 WHERE email = 'admin@admin.com';
