## Introduction
This project is a Task Management API built using Laravel, designed to handle tasks with varying priority, status, and due dates. The API provides full CRUD functionality for both tasks and users, and includes advanced features such as role-based access control (RBAC) using the Spatie Permissions package, JWT-based authentication for securing endpoints, Soft Deletes for tasks and users, and custom scopes for filtering tasks by priority and status. The system is structured to adhere to RESTful standards, ensuring that all HTTP status codes, data validation, and error handling are correctly implemented.
## Prerequisites

- [PHP](https://www.php.net/) >= 8.0
- [Composer](https://getcomposer.org/)
- [Laravel](https://laravel.com/) >= 9.0
- [MySQL](https://www.mysql.com/) or any other database supported by Laravel
- [Postman](https://www.postman.com/) for testing API endpoints

## Setup

1. **Clone the project:**
   git clone https://github.com/SafaaNahhas/TaskManegment.git
   cd movie-library
## Install backend dependencies:
composer install
Create the .env file:
Copy the .env.example file to .env:
cp .env.example .env
## modify the .env file to set up your database connection:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
## Generate the application key:
php artisan key:generate
## Run migrations:
php artisan migrate
## Start the local server:
php artisan serve
You can now access the project at http://localhost:8000.

## Project Structure
- `TaskController.php`: Handles API requests related to tasks, such as creating, updating, deleting, and retrieving tasks.
- `UserController.php` : Handles API requests related to user management, including creating and updating user profiles.
- `AuthController.php`: Manages API requests related to user authentication, including registration, login, and token management.
-`TaskService.php`: Contains the business logic for managing tasks.
BorrowRecordService.php: Contains the business logic for managing borrow records.
- `UserService.php`: Contains the business logic for managing users.
- `AuthService.php`: Contains the business logic for managing user authentication, including validating credentials and generating JWT tokens.
- `ApiResponseService.php`: A service class responsible for formatting and returning standardized API responses.
- `StoreTaskRequest.php`: A Form Request class for validating data when creating tasks.
- `UpdateStatusRequest.php`: A Form Request class for validating data when updating status tasks.
- `AssignTaskRequest.php`: A Form Request class for validating data when assign task to user.
- `UpdateTaskRequest.php`: A Form Request class for validating data when updating tasks.
- `RegisterRequest.php`: A Form Request class for validating data during user registration.
- `LoginRequest.php`: A Form Request class for validating data during user login.
- `StoreUserRequest.php`: A Form Request class for validating data when creating users.
- `UpdateUserRequest.php`: A Form Request class for validating data when updating user profiles.
- `api.php`: Contains route definitions representing the API endpoints, mapping HTTP requests to the appropriate controllers.
## Advanced Features

1. Filtering
Books can be filtered by priority , status using query parameters.
Features

2. Task Management:
Users can create, view, update, and delete tasks.
Each task has attributes like title, description, priority, due_date, and status.
Managers can assign tasks to other users, and only the assigned user can modify the task's status or details.
Admins have full control over all tasks, including the ability to delete them permanently using forceDelete.

3. User Management:
The API provides the ability to create, view, update, and delete users.
Users are assigned roles (Admin, Manager, User), each with different access levels to task operations.

4. Role-Based Access Control (RBAC):
The Spatie Permissions package is used to manage roles and permissions.
Admins have full permissions for all tasks and users.
Managers can assign tasks and manage tasks they created or assigned.
Users can only modify tasks assigned to them and update task statuses.

5. JWT Authentication:
JWT (JSON Web Tokens) are used for user authentication, securing access to the API.
Only authenticated users can perform operations on tasks and users.

6. Soft Deletes:
Tasks and users are soft-deleted, meaning they can be restored if needed.
Deleted tasks are not removed permanently unless forceDelete is called.

7. Task Assignment:
Managers can assign tasks to other users using the assigned_to field.
Only assigned users can modify the task details or mark it as completed.

8. Date Handling:
Task due dates are handled using Carbon with Accessors and Mutators for custom formatting (e.g., d-m-Y H:i).
Tasks can be automatically marked as "overdue" if the current date exceeds the due_date.

9. Task Scopes:
Custom query scopes are provided to filter tasks by priority and status.

10. Seeders
A PermissionsSeeder is provided to create roles and permissions in the database:
Admin is granted all permissions (create, view, update, delete tasks, etc.).
Manager is granted permissions to create, assign, and manage tasks, but not delete users.
User has limited permissions, mostly to manage their own tasks.

11. Authentication: JWT
JWT-based authentication ensures that all routes are protected and accessible only to authenticated users:
Users must log in with valid credentials to receive a JWT token.
Each request must include this token to authenticate and authorize access to task and user management functionalities.

12. Soft Deletes
Soft deletes allow tasks and users to be marked as deleted without removing their records from the database. This provides the ability to recover tasks or users if needed:
Soft-deleted records can be restored using restore.
Admins can perform permanent deletions using forceDelete.

## A Postman collection
is provided to easily test the API endpoints. You can import it into your Postman application and run the requests.
## Postman Documentation
https://documenter.getpostman.com/view/34501481/2sAXjSy8p1
