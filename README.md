# Task Management System

## Description
This project is a **Task Management System** built with **Laravel 10** that provides a **RESTful API** for managing tasks and users. It allows users and administrators to perform various operations related to task management and user interactions. The system follows the **Fat Models, Skinny Controllers** approach, emphasizing **separation of concerns**, **clean code principles**, and **best practices** for maintainability and scalability.

### Key Features:

- **User Operations**:
  - **Registration**: Users can register an account.
  - **Login/Logout**: Users can log in and out of their accounts.
  - **Change Task Status**: Users can change the status of tasks assigned to them.

- **Admin Operations**:
  - **CRUD for Users**: Administrators can create, read, update, and delete user accounts.
  - **CRUD for Tasks**: Administrators can create, read, update, and delete tasks.
  - **Manage Task Assignments**: Admins can assign tasks to users and manage all task-related activities.

- **Manager Operations**:
  - **CRUD for Tasks Created by Manager**: Managers have full access to tasks they created.
  - **Assign Tasks**: Managers can assign tasks to users even if the task was created by an admin.

- **Form Requests**: Validation is handled by custom form request classes, ensuring data integrity and business logic separation.
- **API Response Service**: Unified responses for API endpoints are managed through a dedicated service, ensuring consistency and clarity in API responses.
- **Pagination**: Results are paginated to improve performance and user experience.
- **Resources**: API responses are formatted using Laravel resources to maintain a consistent structure.
- **Seeders**: The database is populated with initial data for testing and development purposes.

### Key Principles:
- **Separation of Concerns**: Business logic is moved to models and service classes to ensure that controllers remain lean.
- **Clean Code**: The project adheres to clean code principles, including the use of timestamps, `fillable`, `guarded`, `primaryKey`, and `table` attributes in models.
- **Query Scopes**: Implemented to simplify and reuse query logic throughout the application.
- **Services**: Business logic is encapsulated in service classes to promote reusability and maintainability.

### Technologies Used:
- **Laravel 10**
- **PHP**
- **MySQL**
- **XAMPP** 
- **Composer** 
- **Postman Collection**: Contains all API requests for easy testing and interaction with the API.

---

## Installation

### Prerequisites

Ensure you have the following installed on your machine:
- **XAMPP**: For running MySQL and Apache servers locally.
- **Composer**: For PHP dependency management.
- **PHP**: Required for running Laravel.
- **MySQL**: Database for the project.
- **Postman**: Required for testing the requests.

### Steps to Run the Project

1. Clone the Repository  
   ```bash
   git clone https://github.com/TukaHeba/Task_System.git
2. Navigate to the Project Directory
   ```bash
   cd Task_System
3. Install Dependencies
   ```bash
   composer install
4. Create Environment File
   ```bash
   cp .env.example .env
   Update the .env file with your database configuration (MySQL credentials, database name, etc.).
5. Generate Application Key
    ```bash
    php artisan key:generate
6. Run Migrations
    ```bash
    php artisan migrate
7. Seed the Database
    ```bash
    php artisan db:seed
8. Run the Application
    ```bash
    php artisan serve
9. Interact with the API and test the various endpoints via Postman collection 
    Get the collection from here: https://documenter.getpostman.com/view/34424205/2sAXjRW9u6
