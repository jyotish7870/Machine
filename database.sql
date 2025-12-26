-- Create database (comment out - database already exists on Hostinger)
-- CREATE DATABASE IF NOT EXISTS company_db;
USE u926020147_company;

-- Admin users table
CREATE TABLE IF NOT EXISTS admin_users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Categories table
CREATE TABLE IF NOT EXISTS categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL,
    parent_id INT DEFAULT NULL,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Products table
CREATE TABLE IF NOT EXISTS products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    short_description TEXT,
    description TEXT,
    media_type ENUM('image', 'video') DEFAULT 'image',
    media_path VARCHAR(255) NOT NULL,
    category_id INT DEFAULT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Product images table (for multiple images per product)
CREATE TABLE IF NOT EXISTS product_images (
    id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    is_primary TINYINT DEFAULT 0,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Product specifications table
CREATE TABLE IF NOT EXISTS product_specifications (
    id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT NOT NULL,
    spec_name VARCHAR(100) NOT NULL,
    spec_value VARCHAR(255) NOT NULL,
    display_order INT DEFAULT 0,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Spare parts table
CREATE TABLE IF NOT EXISTS spare_parts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    image_path VARCHAR(255),
    price VARCHAR(50),
    status ENUM('available', 'out_of_stock') DEFAULT 'available',
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Spare part specifications table
CREATE TABLE IF NOT EXISTS spare_part_specs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    spare_part_id INT NOT NULL,
    spec_name VARCHAR(100) NOT NULL,
    spec_value VARCHAR(255) NOT NULL,
    FOREIGN KEY (spare_part_id) REFERENCES spare_parts(id) ON DELETE CASCADE
);

-- Site settings table (for dynamic content)
CREATE TABLE IF NOT EXISTS site_settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    setting_type ENUM('text', 'textarea', 'image', 'html') DEFAULT 'text',
    setting_group VARCHAR(50) DEFAULT 'general',
    setting_label VARCHAR(100),
    display_order INT DEFAULT 0,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default admin user (username: admin, password: admin123)
-- Password hash is generated using password_hash('admin123', PASSWORD_DEFAULT)
INSERT INTO admin_users (username, password, email) 
VALUES ('admin', '$2y$10$t77inp7m6DsdzLi7arb1nOZA4jJvRRneOiOeza7FudcYDKj6A62iC', 'admin@company.com')
ON DUPLICATE KEY UPDATE username=username;

-- Insert default categories
INSERT INTO categories (name, slug, display_order) VALUES 
('Weigher Machine', 'weigher-machine', 1),
('Packing Machine', 'packing-machine', 2),
('Sealing Machine', 'sealing-machine', 3),
('Filling Machine', 'filling-machine', 4)
ON DUPLICATE KEY UPDATE name=name;

-- Insert default site settings
INSERT INTO site_settings (setting_key, setting_value, setting_type, setting_group, setting_label, display_order) VALUES
-- Header Settings
('site_name', 'Company Machines', 'text', 'header', 'Site Name', 1),
('site_tagline', 'Quality Industrial Machines', 'text', 'header', 'Site Tagline', 2),
('header_phone', '+91-9999999999', 'text', 'header', 'Header Phone', 3),
('header_email', 'info@company.com', 'text', 'header', 'Header Email', 4),

-- Footer Settings
('footer_about', 'We are a leading provider of industrial machines and equipment. Quality and customer satisfaction are our top priorities.', 'textarea', 'footer', 'Footer About Text', 1),
('footer_address', '123 Industrial Area, City, State - 123456', 'textarea', 'footer', 'Footer Address', 2),
('footer_phone', '+91-9999999999', 'text', 'footer', 'Footer Phone', 3),
('footer_email', 'info@company.com', 'text', 'footer', 'Footer Email', 4),
('footer_copyright', '© 2025 Company Machines. All Rights Reserved.', 'text', 'footer', 'Copyright Text', 5),

-- Social Links
('social_facebook', 'https://facebook.com/', 'text', 'social', 'Facebook URL', 1),
('social_twitter', 'https://twitter.com/', 'text', 'social', 'Twitter URL', 2),
('social_instagram', 'https://instagram.com/', 'text', 'social', 'Instagram URL', 3),
('social_linkedin', 'https://linkedin.com/', 'text', 'social', 'LinkedIn URL', 4),
('social_youtube', 'https://youtube.com/', 'text', 'social', 'YouTube URL', 5),
('social_whatsapp', '+919999999999', 'text', 'social', 'WhatsApp Number', 6),

-- About Page
('about_title', 'About Us', 'text', 'about', 'Page Title', 1),
('about_subtitle', 'Your trusted partner in industrial machinery', 'text', 'about', 'Page Subtitle', 2),
('about_content', 'We are a leading manufacturer and supplier of industrial machines. With years of experience in the industry, we provide high-quality machines that meet international standards.', 'textarea', 'about', 'Main Content', 3),
('about_mission', 'To provide high-quality industrial machines that enhance productivity and efficiency for our clients worldwide.', 'textarea', 'about', 'Our Mission', 4),
('about_vision', 'To be the global leader in industrial machinery solutions, known for innovation, quality, and reliability.', 'textarea', 'about', 'Our Vision', 5),

-- Contact Page
('contact_title', 'Contact Us', 'text', 'contact', 'Page Title', 1),
('contact_subtitle', 'Get in touch with us for any queries', 'text', 'contact', 'Page Subtitle', 2),
('contact_address', '123 Industrial Area, Main Road, City, State - 123456, India', 'textarea', 'contact', 'Full Address', 3),
('contact_phone1', '+91-9999999999', 'text', 'contact', 'Phone Number 1', 4),
('contact_phone2', '+91-8888888888', 'text', 'contact', 'Phone Number 2', 5),
('contact_email1', 'info@company.com', 'text', 'contact', 'Email 1', 6),
('contact_email2', 'sales@company.com', 'text', 'contact', 'Email 2', 7),
('contact_timing', 'Mon - Sat: 9:00 AM - 6:00 PM', 'text', 'contact', 'Business Hours', 8),
('contact_map', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3024.2219901290355!2d-74.00369368400567!3d40.71312937933185!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c25a23e28c1191%3A0x49f75d3281df052a!2s150%20Park%20Row%2C%20New%20York%2C%20NY%2010007%2C%20USA!5e0!3m2!1sen!2sin!4v1616661339498!5m2!1sen!2sin', 'textarea', 'contact', 'Google Maps Embed URL', 9),

-- Home Page
('home_hero_title', 'Welcome to Company Machines', 'text', 'home', 'Hero Title', 1),
('home_hero_subtitle', 'Your trusted partner for industrial machinery solutions', 'text', 'home', 'Hero Subtitle', 2),
('home_cta_title', 'Need a Custom Solution?', 'text', 'home', 'CTA Section Title', 3),
('home_cta_text', 'Contact us for custom industrial machine solutions tailored to your needs', 'text', 'home', 'CTA Section Text', 4),

-- Features
('feature1_title', 'Quality Machines', 'text', 'features', 'Feature 1 Title', 1),
('feature1_text', 'We provide high-quality industrial machines with latest technology.', 'text', 'features', 'Feature 1 Text', 2),
('feature1_icon', 'fas fa-cogs', 'text', 'features', 'Feature 1 Icon', 3),
('feature2_title', '24/7 Support', 'text', 'features', 'Feature 2 Title', 4),
('feature2_text', 'Round-the-clock customer support for all your queries and issues.', 'text', 'features', 'Feature 2 Text', 5),
('feature2_icon', 'fas fa-headset', 'text', 'features', 'Feature 2 Icon', 6),
('feature3_title', 'Fast Delivery', 'text', 'features', 'Feature 3 Title', 7),
('feature3_text', 'Quick and secure delivery to your location anywhere.', 'text', 'features', 'Feature 3 Text', 8),
('feature3_icon', 'fas fa-truck', 'text', 'features', 'Feature 3 Icon', 9),
('feature4_title', 'Warranty', 'text', 'features', 'Feature 4 Title', 10),
('feature4_text', 'All products come with comprehensive warranty coverage.', 'text', 'features', 'Feature 4 Text', 11),
('feature4_icon', 'fas fa-shield-alt', 'text', 'features', 'Feature 4 Icon', 12)
ON DUPLICATE KEY UPDATE setting_key=setting_key;
