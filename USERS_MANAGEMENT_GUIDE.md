# User Management System - Documentation

## Overview

The User Management System is a comprehensive role-based user administration module designed for the School Management System. It allows administrators to create, manage, and organize users with different roles including:
- **Students**
- **Teachers**
- **Non-Teaching Staff (NTS)**
- **Administrators**

## Features

### 1. **Role-Based User Management**
- Multiple user roles with specific permissions and features
- Role-specific fields (e.g., professional qualifications for teachers)
- Easy role assignment during user creation

### 2. **User Roles**

#### **Student Role**
- Access to student portal
- View classes and schedules
- Access to assignments and exam results
- View fee information

#### **Teacher Role**
- Create and manage assignments
- Mark attendance
- Enter exam marks
- Access to class management
- Professional information fields:
  - Qualification
  - Specialization
  - Years of Experience
  - Department

#### **Non-Teaching Staff (NTS) Role**
- General staff management
- Administrative support functions
- Professional information tracking

#### **Administrator Role**
- Full system access
- User management
- System configuration
- Report generation

### 3. **User Management Features**

#### **Add New User**
- Create users with comprehensive information
- Generate random passwords automatically
- Assign roles and departments
- Set joining dates
- Add personal and contact information

#### **Edit User**
- Update user information
- Change user roles and status
- Reset passwords
- Modify professional information

#### **View Users**
- List all users with filters
- Search by username, email, or name
- Filter by role (Student, Teacher, Staff, Admin)
- Filter by status (Active, Inactive)
- View user statistics

#### **User Statistics**
- Total users count
- Count by role (Administrators, Teachers, Students, Staff)
- Active/Inactive users count

### 4. **Security Features**
- Password hashing using PHP's password_hash()
- Email and username uniqueness validation
- Input validation for all fields
- SQL injection prevention
- Session-based authentication

### 5. **User Fields**

#### **Basic Information**
- Username (unique, 3-50 characters)
- Password (minimum 6 characters)
- Email (unique, valid format)
- First Name (required)
- Last Name
- Phone
- Date of Birth
- Gender

#### **Address Information**
- Address
- City
- State
- Pincode

#### **Professional Information (Teachers & Staff)**
- Department
- Qualification
- Specialization
- Years of Experience
- Joining Date

#### **System Information**
- Role (Student, Teacher, NTS, Admin)
- Status (Active, Inactive)
- Created Date
- Last Updated
- Last Login

## How to Use

### **Accessing User Management**
1. Login to the admin dashboard
2. Click on **"Users"** in the navigation menu
3. Click on the **"All Users"** submenu

### **Adding a New User**

1. Click **"+ Add New User"** button
2. Select the user role (Student, Teacher, Staff, Admin)
3. Fill in basic information:
   - Username (must be unique)
   - Email (must be unique)
   - First Name
   - Last Name (optional)
4. Set password:
   - Either enter a password manually (minimum 6 characters)
   - Or check "Generate Random Password" to auto-generate
5. Fill contact information (optional):
   - Phone
   - Gender
   - Date of Birth
6. Enter address information (optional)
7. For Teachers and Staff members, fill professional information
8. Click **"Add User"** to save

### **Editing an Existing User**

1. Go to Users Management page
2. Find the user in the list
3. Click the **"Edit"** button
4. Update any information as needed
5. To change password:
   - Check "Change Password" checkbox
   - Enter new password (minimum 6 characters)
   - Confirm password
   - Click "Update User"

### **Deleting a User**

1. Go to Users Management page
2. Find the user in the list
3. Click the **"Delete"** button
4. Confirm the deletion

### **Filtering and Searching**

**Search by Name/Email/Username:**
1. Enter the search term in the search box
2. Click "Filter" or press Enter

**Filter by Role:**
1. Select role from dropdown (Administrator, Teacher, Student, Non-Teaching Staff)
2. Click "Filter"

**Filter by Status:**
1. Select status from dropdown (Active, Inactive)
2. Click "Filter"

**Reset Filters:**
- Click the "Reset" button to clear all filters and view all users

## Database Schema

### **Users Table**

```sql
CREATE TABLE users (
  id INT(11) PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(50) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  first_name VARCHAR(100) NOT NULL,
  last_name VARCHAR(100),
  phone VARCHAR(15),
  date_of_birth DATE,
  gender VARCHAR(10),
  address TEXT,
  city VARCHAR(50),
  state VARCHAR(50),
  pincode VARCHAR(10),
  role VARCHAR(30) NOT NULL DEFAULT 'student',
  department VARCHAR(100),
  qualification VARCHAR(100),
  specialization VARCHAR(100),
  experience_years INT(11),
  joining_date DATE,
  status VARCHAR(20) DEFAULT 'active',
  profile_image VARCHAR(255),
  last_login DATETIME,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

## Files Used

1. **users_functions.php** - Helper functions for user management
   - `addUser()` - Add new user
   - `getUserById()` - Retrieve single user
   - `getAllUsers()` - Retrieve all users with filters
   - `updateUser()` - Update user information
   - `updateUserPassword()` - Change user password
   - `deleteUser()` - Delete user
   - `getUserStatistics()` - Get user statistics
   - `validateUserInput()` - Validate user input

2. **users.php** - Main user management page
   - Display all users
   - Filtering and search
   - User statistics
   - Action buttons (Edit, Delete)

3. **users_add.php** - Add new user form
   - Comprehensive user creation form
   - Role-specific fields
   - Password generation option
   - Form validation

4. **users_edit.php** - Edit user form
   - Update user information
   - Change password option
   - Status management
   - Role modification

5. **header.php** - Updated navigation menu
   - Added "Users" dropdown menu
   - Links to user management pages

## Default Credentials

After setting up the system, a default admin user is created:

- **Username:** admin_user
- **Password:** (generated automatically)
- **Role:** Administrator
- **Email:** admin_user@school.com

**Note:** Change this password immediately after first login for security.

## Password Management

- **Minimum Password Length:** 6 characters
- **Password Encryption:** PHP password_hash() with DEFAULT algorithm
- **Random Password Generation:** 12 characters with mixed case letters, numbers, and special characters
- **Password Reset:** Available in the edit user form

## Username Guidelines

- **Length:** 3-50 characters
- **Allowed Characters:** Letters (a-z, A-Z), Numbers (0-9), Underscore (_), Hyphen (-)
- **Must be Unique:** No two users can have the same username
- **Examples:** john_smith, teacher123, admin-user

## Email Validation

- Must be a valid email format
- Must be unique in the system
- Used for system notifications and password recovery

## Status Management

- **Active:** User can access the system
- **Inactive:** User account is disabled and cannot access the system

## Role-Based Access

- **Admin:** Full access to all modules
- **Teacher:** Access to academics module (Classes, Assignments, Marks)
- **Student:** Access to student module (Grades, Reports, Assignments)
- **NTS:** Limited access based on assigned permissions

## Tips and Best Practices

1. **Username Convention:** Use lowercase with underscores (e.g., john_doe, mary_smith)
2. **Email Format:** Use institutional email format (e.g., name@school.com)
3. **Password Security:** Encourage strong passwords
4. **Regular Audits:** Review active users periodically
5. **Backup:** Regular database backups are recommended
6. **Archive Inactive:** Consider marking unused accounts as inactive
7. **Documentation:** Keep records of user roles and access levels

## Troubleshooting

### **User Not Found**
- Ensure the user ID is correct
- Check if user status is active
- Verify database connection

### **Duplicate Username Error**
- Username already exists in the system
- Choose a different username
- Check for similar variations

### **Duplicate Email Error**
- Email is already associated with another user
- Use a different email address

### **Password Update Failed**
- New password may not meet minimum length requirements
- Passwords don't match in confirmation field

## Support

For issues or feature requests, contact the system administrator or development team.

## Version History

- **v1.0** - Initial release with full user management system
  - Multiple roles support
  - Comprehensive user management
  - Role-based fields
  - User statistics dashboard

---

**Last Updated:** 2024
**Maintained by:** School Management System Team
