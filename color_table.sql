

--Create table if it doesn't exist
CREATE TABLE IF NOT EXISTS colors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL,
    hex_value VARCHAR(7) UNIQUE NOT NULL
);

--Insert colors only if they don't already exist
INSERT IGNORE INTO colors (name, hex_value) VALUES
('Red', '#e74c3c'),
('Orange', '#e67e22'),
('Yellow', '#f1c40f'),
('Green', '#27ae60'),
('Blue', '#2980b9'),
('Purple', '#8e44ad'),
('Grey', '#95a5a6'),
('Brown', '#7f5539'),
('Black', '#2c2c2c'),
('Teal', '#008080');

-- Show what's in the table
SELECT * FROM colors ;