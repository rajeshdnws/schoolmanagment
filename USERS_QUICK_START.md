# User Management System - Quick Start Guide

## 🚀 Getting Started

### Step 1: Database Setup
1. Open phpMyAdmin
2. Create a new database: `school_managements`
3. Import the `database.sql` file that includes the `users` table

### Step 2: Access the System
1. Navigate to: `http://dps.local/admin/`
2. Login with your admin credentials
3. Click on **"Users"** in the navigation menu

### Step 3: Create Your First User

#### To Add a Student:
1. Click **Users** → **Add User**
2. Select Role: **Student**
3. Fill in:
   - Username: `student_001`
   - Email: `student@school.com`
   - First Name: `John`
   - Last Name: `Doe`
   - Password: Enter or click "Generate Password"
4. Click **Add User**

#### To Add a Teacher:
1. Click **Users** → **Add User**
2. Select Role: **Teacher**
3. Fill Basic Information
4. Fill Professional Information:
   - Qualification: `B.A.`
   - Specialization: `English`
   - Experience: `5` years
5. Click **Add User**

#### To Add Staff:
1. Click **Users** → **Add User**
2. Select Role: **Non-Teaching Staff**
3. Fill in required information
4. Add professional details (optional)
5. Click **Add User**

#### To Add Admin:
1. Click **Users** → **Add User**
2. Select Role: **Administrator**
3. Fill in all required fields
4. Click **Add User**

### Step 4: Manage Users

#### View All Users:
- Go to **Users** → **All Users**

#### Search Users:
- Use the search box to find by username, email, or name

#### Filter Users:
- Filter by Role: Select from dropdown
- Filter by Status: Active/Inactive
- Click **Filter** button

#### Edit User:
- Click **Edit** button next to the user
- Update information
- To change password: Check "Change Password" and enter new password
- Click **Update User**

#### Delete User:
- Click **Delete** button next to the user
- Confirm deletion

## 📊 User Statistics

The dashboard shows:
- **Total Users**: All users in the system
- **Administrators**: Count of admin users
- **Teachers**: Count of teacher users
- **Students**: Count of student users
- **Non-Teaching Staff**: Count of NTS users
- **Active Users**: Count of active accounts

## 🔐 Important Information

### Default Admin User
- **Username**: admin_user
- **Email**: admin_user@school.com
- **Role**: Administrator

### Password Policies
- Minimum: 6 characters
- Contains: Letters, Numbers, Special Characters
- Auto-generation available: Yes

### Username Rules
- Length: 3-50 characters
- Allowed: Letters, Numbers, Underscore (_), Hyphen (-)
- Must be unique

### Email Rules
- Must be valid format
- Must be unique in system

## 🎯 Common Tasks

### Create 10 Test Users
```
Students:
- student_001 | student1@school.com
- student_002 | student2@school.com
- student_003 | student3@school.com
- student_004 | student4@school.com
- student_005 | student5@school.com

Teachers:
- teacher_001 | teacher1@school.com
- teacher_002 | teacher2@school.com

Staff:
- staff_001 | staff1@school.com
- staff_002 | staff2@school.com

Admin:
- admin_user_2 | admin2@school.com
```

### Bulk Actions
While the current system handles one user at a time, you can:
1. Add multiple users sequentially
2. Use scripts for bulk imports (coming in v2.0)
3. Export user list for records

### Deactivate a User
1. Go to Users list
2. Click Edit on the user
3. Change Status to "Inactive"
4. Click Update User

### Generate Random Password
1. When adding a user, check "Generate Random Password"
2. The password appears after form submission
3. Save it securely to share with user

## 📱 Mobile Responsive

The User Management System is fully responsive:
- **Desktop**: Full grid layout
- **Tablet**: Optimized 2-column layout
- **Mobile**: Single column layout

## 🐛 Troubleshooting

| Problem | Solution |
|---------|----------|
| Username exists error | Choose a different username |
| Email exists error | Use a different email address |
| Password too short | Enter minimum 6 characters |
| User not found | Verify user ID in URL |
| Cannot delete user | User might be referenced by other data |

## 📑 Files Structure

```
/admin/
├── users.php (Main user list)
├── users_add.php (Add new user)
├── users_edit.php (Edit user)
├── users_functions.php (Helper functions)
├── header.php (Updated with Users menu)
├── footer.php (Footer)
└── session.php (Authentication)

/assets/css/
├── style.css (Main styling)
└── responsive.css (Mobile responsive)

/database.sql (Updated with users table)
```

## 🔗 Navigation

From Admin Dashboard:
1. **Users** dropdown menu
   - All Users
   - Add User

From Users List:
- **+ Add New User** button (top right)
- **Edit** button (per user)
- **Delete** button (per user)

From Add/Edit Form:
- **← Back to Users** button (top right)
- **Cancel** button (bottom)

## ✅ Verification Checklist

After setup, verify:
- [ ] Users table created in database
- [ ] Can see Users menu in admin navigation
- [ ] Can view empty users list
- [ ] Can create a new user
- [ ] Can edit an existing user
- [ ] Can delete a user
- [ ] Search and filters work
- [ ] Statistics display correctly
- [ ] Role-specific fields appear/hide correctly

## 📞 Support

**Need Help?**
- Check USERS_MANAGEMENT_GUIDE.md for detailed documentation
- Review database.sql for schema information
- Check browser console for JavaScript errors
- Verify database connection in db_config.php

## 🎓 Learning Resources

### Role Selection
- **Student**: View grades, assignments
- **Teacher**: Create assignments, mark attendance, enter grades
- **Non-Teaching Staff**: Administrative tasks
- **Administrator**: Full system control

### Best Practices
1. Use descriptive usernames
2. Keep passwords secure
3. Regularly review active users
4. Archive old accounts as inactive
5. Maintain backup of user data

---

**Version**: 1.0  
**Last Updated**: 2024  
**System**: School Management System
