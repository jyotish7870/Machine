# Company Website - Installation Guide

## Requirements
- XAMPP (Apache + MySQL + PHP)
- Web Browser
- Text Editor (optional)

## Installation Steps

### 1. Setup Database
1. Start XAMPP Control Panel
2. Start Apache and MySQL services
3. Open phpMyAdmin: http://localhost/phpmyadmin
4. Click on "SQL" tab
5. Copy and paste the contents of `database.sql` file
6. Click "Go" to execute the SQL commands

### 2. Configure WhatsApp Number
1. Open `includes/header.php`
2. Find line: `<a href="https://wa.me/1234567890"`
3. Replace `1234567890` with your WhatsApp number (with country code, no + sign)
4. Example: For +1-234-567-890, use: `https://wa.me/11234567890`

### 3. Access the Website
- **Public Website**: http://localhost/company/
- **Admin Panel**: http://localhost/company/admin/login.php

### 4. Default Admin Credentials
- **Username**: admin
- **Password**: admin123

**Important**: Change the default password after first login!

## Features

### Public Section
- **Home Page**: Auto-sliding image carousel with product showcase
- **About Page**: Company information and values
- **Products Page**: Grid view of all active products with images/videos
- **Contact Page**: Contact form with map integration
- **WhatsApp Support**: Floating WhatsApp button for instant messaging

### Admin Panel
- **Dashboard**: Statistics overview
- **Add Products**: Upload images/videos with descriptions
- **Manage Products**: Edit, delete, and reorder products
- **Status Control**: Activate/deactivate products

## File Structure
```
company/
├── admin/
│   ├── login.php          # Admin login page
│   ├── dashboard.php      # Admin dashboard
│   ├── add_product.php    # Add new products
│   ├── edit_product.php   # Edit existing products
│   ├── products.php       # Manage all products
│   └── logout.php         # Logout handler
├── css/
│   └── style.css          # All styling and animations
├── js/
│   └── script.js          # JavaScript functionality
├── includes/
│   ├── header.php         # Header with navigation
│   └── footer.php         # Footer with links
├── uploads/               # Product images/videos (auto-created)
├── config.php             # Database configuration
├── database.sql           # Database structure
├── index.php              # Home page
├── about.php              # About page
├── products.php           # Products listing
├── contact.php            # Contact page
└── README.md              # This file
```

## Usage Instructions

### Adding Products
1. Login to admin panel
2. Click "Add Product" in sidebar
3. Fill in product details:
   - Title (required)
   - Description (optional)
   - Media Type (Image or Video)
   - Upload file
   - Display order (for sorting)
   - Status (Active/Inactive)
4. Click "Add Product"

### Managing Products
1. Go to "Manage Products" in admin panel
2. View all products in table format
3. Click edit icon to modify product
4. Click delete icon to remove product
5. Only active products appear on public website

### Customization
- **Logo**: Edit the h1 text in `includes/header.php`
- **Colors**: Modify CSS variables in `css/style.css` (:root section)
- **Contact Info**: Update details in `contact.php` and `includes/footer.php`
- **Slider Speed**: Change interval in `js/script.js` (default: 5000ms)

## Animations
- Auto-sliding carousel (5 seconds per slide)
- Fade-in animations for product cards
- Hover effects on all interactive elements
- Video auto-play on hover
- Smooth scroll navigation
- WhatsApp button pulse animation

## Browser Support
- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)

## Troubleshooting

### Database Connection Error
- Check if MySQL is running in XAMPP
- Verify database credentials in `config.php`
- Ensure database name matches

### Images Not Displaying
- Check if `uploads/` folder exists
- Verify file permissions (should be writable)
- Check file path in database

### Admin Can't Login
- Verify database was imported correctly
- Check if admin_users table has data
- Try resetting admin password using phpMyAdmin

### WhatsApp Button Not Working
- Ensure phone number is in correct format
- Include country code without + sign
- Example: +91-1234567890 → 911234567890

## Security Notes
1. Change default admin password immediately
2. Use strong passwords
3. Keep XAMPP updated
4. Don't expose admin credentials
5. Regular database backups

## Support
For any issues or questions, contact your administrator.

## Version
v1.0.0 - December 2025
