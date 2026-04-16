# School Management System - Admin Panel

A complete School Management System built with Core PHP, MySQL, HTML, CSS, and JavaScript.

## Features

### 1. **Dashboard**
- Quick overview of statistics
- Recent students and teachers
- Key metrics at a glance

### 2. **Student Management**
- Add, edit, view, and delete students
- Track student information (name, email, phone, etc.)
- Manage parent information
- Filter students by class
- Search functionality

### 3. **Teacher Management**
- Add, edit, view, and delete teachers
- Track teacher details (qualification, specialization, experience)
- Manage salary information
- Filter teachers by status
- Search functionality

### 4. **Class Management**
- Add, edit, and delete classes
- Assign class teachers
- View class capacity and enrollment
- Manage class descriptions

### 5. **Attendance Management**
- Record student attendance
- Filter by date and class
- View attendance records

### 6. **Exams Management**
- Add, edit, and manage exams
- Schedule exams for different classes
- Manage exam details (date, time, marks)
- Track exam status (scheduled, ongoing, completed)

### 7. **Fees Management**
- Track student fees
- Mark fees as paid/pending
- Filter by class and payment status
- View total due and collected fees

### 8. **Admin Profile**
- View and edit admin profile
- Change password
- Update contact information

## Installation Instructions

### Step 1: Prerequisites
- XAMPP (or any PHP server with MySQL)
- Apache web server running
- MySQL database server running

### Step 2: Database Setup

1. Open phpMyAdmin (usually at `http://localhost/phpmyadmin`)
2. Click on "SQL" tab
3. Copy and paste the contents of `database.sql` file
4. Click "Go" to execute the SQL

Or use command line:
```bash
mysql -u root -p < database.sql
```

### Step 3: Configuration

The database configuration is already set in `db_config.php`:
- **Host**: localhost
- **User**: root
- **Password**: (empty)
- **Database**: school_management

If your MySQL credentials are different, edit `db_config.php`:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_NAME', 'school_management');
```

### Step 4: Place Files in Web Root

Place all files in your web root directory:
- For XAMPP: `C:\xampp\htdocs\eshan\`
- For WAMP: `C:\wamp\www\eshan\`

### Step 5: Access the Application

1. Make sure Apache and MySQL are running
2. Open your browser
3. Navigate to: `http://eshan.local/` or `http://localhost/eshan/`

## Default Login Credentials

- **Username**: admin
- **Password**: admin123

**Important**: Change the admin password after first login through Settings page.

## File Structure

```
eshan/
├── index.php                 # Login page
├── db_config.php            # Database configuration
├── database.sql             # Database schema
├── admin/
│   ├── session.php          # Session management
│   ├── header.php           # Header/Navigation
│   ├── footer.php           # Footer
│   ├── dashboard.php        # Dashboard
│   ├── students.php         # Student list
│   ├── students_add.php     # Add student form
│   ├── students_edit.php    # Edit student form
│   ├── students_view.php    # View student details
│   ├── teachers.php         # Teacher list
│   ├── teachers_add.php     # Add teacher form
│   ├── teachers_edit.php    # Edit teacher form
│   ├── teachers_view.php    # View teacher details
│   ├── classes.php          # Classes list
│   ├── classes_add.php      # Add class form
│   ├── classes_edit.php     # Edit class form
│   ├── attendance.php       # Attendance management
│   ├── exams.php            # Exams list
│   ├── exams_add.php        # Add exam form
│   ├── exams_edit.php       # Edit exam form
│   ├── fees.php             # Fees management
│   ├── profile.php          # Admin profile
│   ├── settings.php         # Admin settings
│   └── logout.php           # Logout
├── assets/
│   ├── css/
│   │   ├── style.css        # Main stylesheet
│   │   └── responsive.css   # Responsive design
│   └── js/
│       └── script.js        # JavaScripts functions
└── README.md                # This file
```

## Features Details

### Database Tables

1. **admin** - Admin users
2. **students** - Student information
3. **teachers** - Teacher information
4. **classes** - Class details
5. **subjects** - Subject list
6. **class_subjects** - Subject assignment to classes
7. **attendance** - Student attendance records
8. **assignments** - Class assignments
9. **exams** - Exam details
10. **marks** - Student exam marks
11. **fees** - Fee records
12. **notifications** - System notifications

## Usage Guide

### Adding a Student
1. Click "Students" → "Add Student"
2. Fill in all required fields
3. Click "Add Student" to save

### Managing Teachers
1. Click "Teachers" → "Add Teacher"
2. Enter teacher details
3. Save the teacher record

### Managing Classes
1. Click "Classes" → "Add Class"
2. Enter class name and capacity
3. Assign a class teacher (optional)
4. Save the class

### Recording Attendance
1. Click "Attendance"
2. Select date and class
3. Mark attendance for students
4. Click "Save"

### Managing Exams
1. Click "Exams" → "Add Exam"
2. Enter exam details
3. Select class and subject
4. Set date, time, and total marks
5. Save the exam

### Fee Management
1. Click "Fees"
2. View all fees records
3. Filter by class or payment status
4. Mark fees as paid using "Mark Paid" button

## Important Notes

1. **Security**: Always change the default admin password immediately
2. **Backup**: Regularly backup your database
3. **PHP Version**: Requires PHP 5.4 or higher
4. **MySQL**: Requires MySQL 5.0 or higher

## Security Features

- Password hashing for secure storage
- Session-based authentication
- SQL injection prevention through parameterized queries
- XSS protection through htmlspecialchars()
- CSRF protection through form validation

## Troubleshooting

### Database Connection Error
- Check if MySQL is running
- Verify database credentials in `db_config.php`
- Make sure database `school_management` exists

### Login Failed
- Verify username and password are correct
- Check if admin user exists in database
- Clear browser cookies and try again

### Pages Not Found
- Check if all files are in correct directories
- Verify file permissions
- Check Apache error logs

## Future Enhancements

- Email notifications
- SMS alerts
- Mobile app integration
- Advanced reporting
- Biometric attendance
- Online student portal
- Parent portal
- Performance analytics

## Support

For issues or questions, please contact the development team.

## License

This School Management System is provided as-is for educational and commercial use.

---

**Version**: 1.0  
**Last Updated**: 2024  
**Developer**: Admin Panel Team
