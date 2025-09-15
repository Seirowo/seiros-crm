# Mini-CRM

## 🎯 Project Overview

This is a Laravel test project designed to manage companies and their employees. The primary goal of this system is to serve as a practical assessment, covering essential to intermediate Laravel topics such as MVC, authentication, CRUD operations, Eloquent relationships, migrations, form validation, and pagination.

---

## ✨ Main Features

The system allows an administrator to manage company and employee data with the following functionalities.

### The Companies

Each company entries have:
-   `name` (required)
-   `email` (required)
-   `website`
-   `logo`
-   `description`
-   **Validation:** The logo file size must be a maximum of 2MB with a minimum dimension of 100x100 pixels.

### The Employees

Each employee entries have:
-   `company` (a foreign key to `Companies`)
-   `name` (required)
-   `email` (required)
-   `phone` (required)

### Authentication

-   The system provides a basic login functionality for administrators.

---

## 💻 Technical Requirements

This project applied the following techniques and standards:

-   [x] **MVC Pattern:** Proper use of Models, Views, and Controllers.
-   [x] **Resource Controllers:** Use basic Laravel resource controllers with default methods (index, create, store, etc.).
-   [x] **Form Validation:** Utilize Laravel's validation functions via Request classes.
-   [x] **Pagination:** Display data lists with 10 entries per page.
-   [x] **Eloquent ORM:** Use Eloquent for database interactions and relationships.
-   [x] **Database Migrations and Seeds:** Create database schemas with migrations and seed the first user with `admin@admin.com` and password `password`.
-   [x] **File Management:** Handle company logo uploads.
-   [x] **Frontend:** Use a basic front-end design.

### Technologies Used

![Laravel 12](https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP 8.3](https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.4.4-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)

---

## 🚀 Advanced Features 

-   **Email Notification:** Send an email whenever a new company is created (using Mailgun or another service).
-   **Multi-Role Access:** Implement different user roles (e.g., admin and company users who can only manage their own employees).
-   **Basic Testing:** Add basic tests using Pest or PHPUnit.
-   **Image Crop:** Basic Image cropping for the company's logo.
-   **Activity Logs:** Basic Logs for Recent Activity in the App.
-   **Custom Frontend Theme:** Use a custom front-end theme like "TailwindCSS."

---

## 📦 Installation

Follow these steps to set up and run the project locally:

1.  **Clone the Repository:**
    ```bash
    git clone (https://github.com/Seirowo/seiros-mini-crm.git)
    cd project-test
    ```

2.  **Install PHP Dependencies:**
    ```bash
    composer install
    ```

3.  **Environment Setup:**
    ```bash
    cp .env.example .env
    ```

4.  **Generate Application Key:**
    ```bash
    php artisan key:generate
    ```

5.  **Run Migrations and Seed Database:**
    ```bash
    php artisan migrate --seed
    ```

6.  **Run the Local Development Server:**
    ```bash
    php artisan serve
    ```
    The application will be running at `http://127.0.0.1:8000`.

---
