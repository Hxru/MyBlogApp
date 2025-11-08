# MyBlogApp - Simple Blog Application

A simple blog application built with HTML, CSS, JavaScript, PHP, and MySQL.

## Features

- User Authentication (Register, Login, Logout)
- Blog Management (Create, Read, Update, Delete)
- Responsive Design
- Markdown Support for Blog Posts
- User Authorization

## Project Structure

```
MyBlogApp/
├── api/             # API endpoints
│   ├── auth/        # Authentication endpoints
│   └── posts/       # Blog post endpoints
├── assets/          # Static assets
│   ├── css/         # Stylesheets
│   └── js/          # JavaScript files
├── config/          # Configuration files
├── includes/        # PHP classes and utilities
├── pages/           # Application pages
└── .env             # Environment variables

```

## Setup Instructions

### Local Development Environment

1. Database Setup:
   - Create a MySQL database named 'myblogapp_db'
   - Import the SQL file from `database_setup.sql`

2. Configuration:
   - Copy `.env.development` to `.env`
   - Update database credentials if needed
   - Default configuration works with XAMPP

3. Local Server Setup:
   - Place the project in your XAMPP's htdocs folder
   - Start Apache and MySQL services
   - Ensure MySQL is running on port 3307 (or update port in .env)

4. Access Local Development:
   - Open your browser and navigate to: http://localhost/MyBlogApp
   - Default admin credentials in development environment

### Production Environment (InfinityFree)

1. Database Setup:
   - Create database in InfinityFree control panel
   - Import the database structure using phpMyAdmin
   - Initial data will be created automatically

2. Configuration:
   - Copy `.env.production` to `.env` on the server
   - Ensure database credentials match InfinityFree settings
   - Update APP_URL to your InfinityFree domain

3. File Upload:
   - Use FTP (FileZilla) to upload files to InfinityFree
   - Ensure proper file permissions (644 for files, 755 for directories)

4. Access Production:
   - Visit your InfinityFree domain (e.g., https://myblogapp-hxru.infinityfreeapp.com)
   - Create admin account through registration

## Technologies Used

- Frontend:
  - HTML5
  - CSS3
  - JavaScript (Vanilla)
  - SimpleMDE (Markdown Editor)

- Backend:
  - PHP 7.4+
  - MySQL 5.7+

## Security Features

- Password Hashing
- JWT Authentication
- Input Sanitization
- XSS Protection
- CSRF Protection

## Error Handling

- User-friendly error messages
- Server-side validation
- Client-side validation
- Proper error logging

## Author

Hiruni Chamodi

## License

This project is licensed under the MIT License.
