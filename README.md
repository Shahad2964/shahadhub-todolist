# DevSecOps To-Do List Application

## Overview:
This is a simple To-Do List web application built with PHP/MySQL to demonstrate **Secure SDLC** and **DevSecOps principles**. The project shows how security can be integrated early in the development process (Shift Left) and automated through a CI/CD pipeline.

## Features:
- User registration and login with hashed passwords
- Add, complete, and delete tasks
- Feedback form with input validation
- Secure database interactions using prepared statements
- Example vulnerabilities introduced and fixed to demonstrate security practices

## Security Focus:
- SQL Injection prevention
- Input validation for forms
- Password hashing
- Demonstration of threat modeling using STRIDE
- Automated security scans in CI/CD pipeline:
  - SAST (SonarQube)
  - DAST (OWASP ZAP)
  - SCA (Dependency-Check)

## Structure:
- `config.php` – database connection
- `todo.php` – main task management page
- `login.php` / `login_process.php` – login system
- `register.php` / `register_process.php` – user registration
- `feedback.php` / `feedback_process.php` – feedback system
- `db.sql` – database schema
- `styles.css` – styling for pages

## Getting Started:
1. Clone the repository
   ```bash
   git clone https://github.com/Shahad2964/shahadhub-todolist.git
