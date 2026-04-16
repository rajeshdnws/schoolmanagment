# School Management System - Setup Guide

## Quick Start

This guide will help you set up the School Management System on your local machine.

## Prerequisites

Before you begin, make sure you have:

1. **XAMPP** or any PHP server (PHP 5.4+)
2. **MySQL** database server (5.0+)
3. **Apache** web server
4. **Web browser** (Chrome, Firefox, Safari, Edge)

## Step 1: Extract Files

Extract the project files to your web root:

### For XAMPP:
```
C:\xampp\htdocs\eshan\
```

### For WAMP:
```
C:\wamp\www\eshan\
```

### For Linux (Apache):
```
/var/www/html/eshan/
```

## Step 2: Start Services

### For XAMPP:
1. Open XAMPP Control Panel
2. Click "Start" next to Apache
3. Click "Start" next to MySQL

### For WAMP:
1. Open WAMP application
2. Ensure all services are green

## Step 3: Create Database

### Option A: Using phpMyAdmin

1. Open browser and go to `http://localhost/phpmyadmin`
2. Click on "SQL" tab
3. Open `database.sql` file from the project
4. Copy and paste the SQL code
5. Click "Go" button

### Option B: Using Command Line

```bash
# Navigate to the project directory
cd C:\xampp\htdocs\eshan

# Import the database
mysql -u root -p < database.sql

# If using XAMPP (no password):
mysql -u root < database.sql
```

### Option C: Using MySQL Workbench

1. Open MySQL Workbench
2. Click "File" → "Open SQL Script"
3. Select `database.sql`
4. Click "Execute" (lightning bolt icon)

## Step 4: Configure Database (if needed)

Edit `db_config.php` if your database credentials are different:

```php
define('DB_HOST', 'localhost');      // Usually localhost
define('DB_USER', 'root');           // Your MySQL username
define('DB_PASS', '');               // Your MySQL password
define('DB_NAME', 'school_management'); // Database name
```

## Step 5: Configure Hosts File (Optional)

To access via `http://eshan.local` instead of `http://localhost/eshan`:

### For Windows:
Edit `C:\Windows\System32\drivers\etc\hosts`

Add this line:
```
127.0.0.1   eshan.local
```

### For Mac:
Edit `/etc/hosts`

Add this line:
```
127.0.0.1   eshan.local
```

### For Linux:
Edit `/etc/hosts`

Add this line:
```
127.0.0.1   eshan.local
```

## Step 6: Access the Application

Open your browser and navigate to:

- `http://localhost/eshan/` (Default)
- `http://eshan.local/` (If you configured hosts file)
- `http://localhost:8080/eshan/` (If using different port)

## Step 7: Login

Use the default credentials:

- **Username**: admin
- **Password**: admin123

## Important: Change Default Password

After first login:

1. Click on your name in top-right corner
2. Select "Settings"
3. Enter new password (min 6 characters)
4. Click "Update Settings"

## Verification

Check if everything is working properly:

1. Open `http://localhost/eshan/setup_verify.php`
2. Review all checks
3. Fix any errors shown

## Troubleshooting

### Issue: "Connection failed: Connection refused"

**Solution:**
1. Make sure MySQL is running
2. Check database credentials in `db_config.php`
3. Verify MySQL port (usually 3306)

```bash
# On Windows, check if MySQL is running:
netstat -an | findstr :3306

# On Linux/Mac:
netstat -an | grep :3306
```

### Issue: "Table already exists" error

**Solution:**
The database already has tables. Either:
1. Delete the database and recreate it
2. Or just proceed with setup

```bash
# Drop and recreate database
mysql -u root -p -e "DROP DATABASE school_management; CREATE DATABASE school_management;"
mysql -u root -p school_management < database.sql
```

### Issue: "Permission Denied" errors

**Solution:**
1. Check folder permissions
2. Make sure Apache has read/write access to the directory

```bash
# On Linux/Mac, set permissions:
chmod -R 755 eshan/
chmod -R 777 eshan/admin/
chmod -R 777 eshan/assets/
```

### Issue: "PHP Parse Error"

**Solution:**
1. Verify PHP version (needs 5.4+)
2. Check `php.ini` settings
3. Enable required extensions: `mysqli`, `json`, `filter`

```php
// Check PHP info
<?php phpinfo(); ?>
```

### Issue: "404 Not Found" on pages

**Solution:**
1. Verify all files are extracted correctly
2. Check folder structure
3. Enable `.htaccess` support in Apache

### Issue: Blank pages or white screen

**Solution:**
1. Check PHP error logs
2. Enable error reporting in `db_config.php`
3. Check browser console for JavaScript errors

## File Permissions

Linux/Mac permissions for security:

```bash
# Make directories
chmod 755 admin/
chmod 755 assets/

# Make files
chmod 644 index.php
chmod 644 db_config.php
chmod 644 database.sql
```

## Backup Database

### Using phpMyAdmin:
1. Go to phpMyAdmin
2. Select database `school_management`
3. Click "Export" tab
4. Click "Go" to download SQL file

### Using Command Line:
```bash
mysqldump -u root -p school_management > backup.sql
```

## Restore Database

```bash
mysql -u root -p school_management < backup.sql
```

## Security Checklist

- [ ] Changed default admin password
- [ ] Configured database credentials
- [ ] Set proper file permissions (755, 644)
- [ ] Removed setup files (optional): `setup_verify.php`, `info.php`
- [ ] Configured `hosts` file if using `eshan.local`
- [ ] Enabled SSL/HTTPS (optional)
- [ ] Configured firewall rules
- [ ] Tested login functionality
- [ ] Verified all features work

## Features to Explore

After successful setup, explore these features:

1. **Dashboard** - Overview of the system
2. **Add Student** - Create new student records
3. **Add Teacher** - Add teacher information
4. **Add Class** - Create classes
5. **Manage Exams** - Schedule and manage exams
6. **Manage Fees** - Track student fees
7. **Attendance** - Record attendance
8. **Profile** - View and edit admin profile

## Additional Configuration

### Email Notifications (Optional)
Edit `db_config.php` to add email configuration:

```php
// Email configuration
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_USER', 'your-email@gmail.com');
define('MAIL_PASS', 'your-app-password');
define('MAIL_PORT', 587);
```

### Session Timeout (Optional)
Edit `admin/session.php` to set session timeout:

```php
// Session timeout in seconds (default: 3600 = 1 hour)
session_set_cookie_params(3600);
```

## Support & Help

- Check `README.md` for feature documentation
- Review `database.sql` for database schema
- Check PHP error logs for debugging
- Verify MySQL connection with `setup_verify.php`

## Next Steps

1. Complete the setup
2. Test all admin panel features
3. Add sample data (students, teachers, classes)
4. Customize the system as needed
5. Deploy to production (configure HTTPS, database backups, etc.)

---

**Enjoy using School Management System!**

For issues or questions, refer to the README.md file or check the error logs.
