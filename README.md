# Admin Dashboard for MyConsultHours Tool

## Overview

This is the admin dashboard for managing and reviewing consultancy hours submitted by consultants. The dashboard provides an overview of the number of consultants, hours logged for the current day, week, and month, and offers functionality to approve consultancy hours.

### Features:
- **Admin Login**: The dashboard is accessible only to authenticated admins.
- **Analytics**: Displays the total number of consultants and hours logged today, this week, and this month.
- **Consultancy Hours Approval**: Allows the admin to view and approve consultancy hours logged by consultants.
- **Admin Management**: Links to add new reviewers and view consultants.
  
## Prerequisites

Before deploying the admin dashboard, ensure that the following software is installed:

- **PHP 7.x or higher**
- **MySQL Database** (or MariaDB)
- **Web Server** (Apache or Nginx)

### Database Setup

1. **Create the Database**: Set up a MySQL or MariaDB database to store your data.
   
2. **Database Tables**:
   - `admins`: Stores admin user details (id, username, password).
   - `users`: Stores consultants' information (id, username, email, etc.).
   - `log_hours`: Stores the consultancy hours logged by consultants (id, user_id, task_name, task_description, hours, date, status).

3. **Database Configuration**: Update the database connection details in the `php/db_connect.php` file with your server's information.

## Installation

### 1. Clone or Upload Files

- Clone the repository or upload the files to your server's web directory.

### 2. Set Up the Database

1. Import or create the necessary database schema (tables `admins`, `users`, and `log_hours`) in your MySQL database.
2. Modify the database connection settings in `php/db_connect.php`:

```php
// php/db_connect.php
$servername = "localhost"; // Database host
$username = "root"; // Database username
$password = ""; // Database password
$dbname = "myconsulthours"; // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
```

### 3. Set Up the Admin Session

- **Admin Login**: Ensure that an admin is already set up in the `admins` table with valid credentials (username, password).
- If you're using plain text passwords, update the login logic to use hashed passwords for security (using `password_hash()` and `password_verify()`).

### 4. Adjust File Permissions

Ensure the following files have appropriate read/write permissions for your web server:
- `php/db_connect.php`
- `css/style.css`
- `script.js`

### 5. Web Server Configuration

Make sure your web server (Apache or Nginx) is properly configured to serve the PHP files. You may need to configure a virtual host to map the dashboard's URL.

## Usage

1. **Login as Admin**: Ensure that you are logged in as an admin before accessing the dashboard. The admin must be authenticated, and the session will be stored in `$_SESSION['admin_id']`.

2. **Dashboard**: Upon logging in, the admin will be redirected to the dashboard where they can see the following:
   - **Analytics**: Displays the number of consultants and the total hours logged for today, this week, and this month.
   - **Approve Consultancy Hours**: Displays a table of consultancy hours with options to approve logged hours.

3. **Actions**:
   - To approve consultancy hours, click the "Approve" button for each entry. The button will be disabled if the entry is already approved.

4. **Logout**: The admin can log out by clicking the "Log Out" button.

## Files Breakdown

- **`index.php`**: Main dashboard view, which fetches and displays analytics and consultancy hours.
- **`php/db_connect.php`**: Handles database connection.
- **`css/style.css`**: Styles the dashboard.
- **`script.js`**: Contains JavaScript for interactivity (like button actions).

## Known Issues

- Make sure your PHP version supports MySQLi or PDO (for database interactions).
- Ensure all database queries are optimized for performance if working with large datasets.

## Contributing

Feel free to fork the repository, submit issues, or make pull requests for improvements.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

