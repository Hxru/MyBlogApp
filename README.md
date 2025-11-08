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

1. Database Setup:
   - Create a MySQL database named 'myblogapp_db'
   - Import the SQL file from `backend/config/database.sql`

2. Configuration:
   - Copy `.env.example` to `.env`
   - Update database credentials in `.env`

3. Server Setup:
   - Place the project in your XAMPP's htdocs folder
   - Start Apache and MySQL services

4. Access the Application:
   - Open your browser and navigate to: http://localhost/MyBlogApp

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
