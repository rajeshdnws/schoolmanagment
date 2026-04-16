# Role-Based Access Control (RBAC) - Test Guide

## Overview
This guide explains how to test the implemented RBAC system to verify that access control is working properly for all user roles.

## Role Permissions Matrix

### Admin Role
- **Dashboard**: ✓ Full Access
- **Students**: ✓ View, Add, Edit, Delete
- **Teachers**: ✓ View, Add, Edit, Delete
- **Classes**: ✓ View, Add, Edit, Delete
- **Attendance**: ✓ View, Add, Edit, Delete
- **Exams**: ✓ View, Add, Edit, Delete
- **Marks**: ✓ View, Add, Edit, Delete
- **Fees**: ✓ View, Add, Edit, Delete
- **Users**: ✓ View, Add, Edit, Delete (User Management)
- **Profile**: ✓ View/Edit
- **Settings**: ✓ Edit

### Teacher Role
- **Dashboard**: ✓ Full Access
- **Students**: ✓ View Only
- **Teachers**: ✗ No Access
- **Classes**: ✓ View Only
- **Attendance**: ✓ View, Add, Edit
- **Exams**: ✓ View, Add, Edit
- **Marks**: ✓ View, Add, Edit
- **Fees**: ✓ View Only
- **Users**: ✗ No Access
- **Profile**: ✓ View/Edit
- **Settings**: ✓ Edit

### Student Role
- **Dashboard**: ✓ Full Access
- **Students**: ✗ No Access
- **Teachers**: ✗ No Access
- **Classes**: ✓ View Only
- **Attendance**: ✓ View Only
- **Exams**: ✓ View Only
- **Marks**: ✓ View Only
- **Fees**: ✓ View Only
- **Users**: ✗ No Access
- **Profile**: ✓ View/Edit
- **Settings**: ✓ Edit

### NTS (Non-Teaching Staff) Role
- **Dashboard**: ✓ Full Access
- **Students**: ✓ View, Add, Edit (No Delete)
- **Teachers**: ✓ View, Add, Edit (No Delete)
- **Classes**: ✓ View, Add, Edit (No Delete)
- **Attendance**: ✗ No Access (View/Add/Edit/Delete)
- **Exams**: ✗ No Access
- **Marks**: ✗ No Access
- **Fees**: ✓ View, Add, Edit (No Delete)
- **Users**: ✗ No Access
- **Profile**: ✓ View/Edit
- **Settings**: ✓ Edit

## Testing Steps

### Step 1: Test Login with Each Role

1. **Admin User**
   - URL: http://dps.local/
   - Username: admin_user
   - Password: admin_password
   - Expected: Login successful, full dashboard access

2. **Teacher User**
   - Create a teacher user via Admin panel
   - Login with teacher credentials
   - Expected: Limited menu (no Teachers link, no Users link)

3. **Student User**
   - Create a student user via Admin panel
   - Login with student credentials
   - Expected: Very limited menu (only Classes, Attendance, Exams, Marks, Fees with read-only)

4. **NTS User**
   - Create an NTS user via Admin panel
   - Login with NTS credentials
   - Expected: Menu shows Students, Teachers, Classes, Fees but not Users, Exams, Marks

### Step 2: Test Role-Based Menu Visibility

After logging in as each role, verify the navigation menu shows only authorized modules:

**For Admin:**
- Should see: Dashboard, Students, Teachers, Classes, Users, Attendance, Exams, Fees
- Should NOT see: Nothing (full menu)

**For Teacher:**
- Should see: Dashboard, Students, Classes, Attendance, Exams
- Should NOT see: Teachers, Users, Fees (fees is hidden but visible)

**For Student:**
- Should see: Dashboard, Classes, Attendance, Exams, Marks, Fees
- Should NOT see: Students, Teachers, Users

**For NTS:**
- Should see: Dashboard, Students, Teachers, Classes, Fees
- Should NOT see: Users, Exams, Marks, Attendance

### Step 3: Test Direct URL Access Attempts

Try accessing pages directly via URL (should show access denied):

1. **Teacher tries to access Users page**
   - URL: http://dps.local/admin/users.php
   - Expected: Redirected to dashboard with error message

2. **Student tries to access Students page**
   - URL: http://dps.local/admin/students.php
   - Expected: Redirected to dashboard with error message

3. **NTS tries to access Exams page**
   - URL: http://dps.local/admin/exams.php
   - Expected: Redirected to dashboard with error message

### Step 4: Test Button/Action Access

1. **Delete Action Testing**
   - Login as Teacher
   - View a student record
   - Expected: Delete button should not appear or be disabled
   
   - Login as Admin
   - View the same student record
   - Expected: Delete button should appear and be enabled

2. **Add Action Testing**
   - Login as Student
   - Try to access http://dps.local/admin/students_add.php
   - Expected: Access denied, redirected to dashboard
   
   - Login as Teacher
   - Try to access http://dps.local/admin/students_add.php
   - Expected: Access denied, redirected to dashboard
   
   - Login as Admin
   - Access http://dps.local/admin/students_add.php
   - Expected: Add form loads successfully

3. **Edit Action Testing**
   - Login as NTS
   - Try to access http://dps.local/admin/users_edit.php?id=1
   - Expected: Access denied
   
   - Login as Admin
   - Access http://dps.local/admin/users_edit.php?id=1
   - Expected: Edit form loads successfully

### Step 5: Test Form Submit Actions

1. **Student Edit Permission**
   - Login as Teacher
   - View a student detail page
   - Try to submit student edit form
   - Expected: Form should not show edit button or form should show error on submit

   - Login as Admin
   - View the same student detail page
   - Submit student edit form
   - Expected: Form submit successful, record updated

2. **Delete Permission on Form Submit**
   - Login as NTS
   - Try to delete a student record
   - Expected: Delete action denied or delete button hidden
   
   - Login as Admin
   - Delete a student record
   - Expected: Record deleted successfully

### Step 6: Test Profile and Settings Access

All logged-in roles should be able to:
1. View their profile: http://dps.local/admin/profile.php
2. Access settings: http://dps.local/admin/settings.php
3. Change password in settings

### Step 7: Test Session Timeout

1. Login as a non-admin user
2. Keep the page open for the session timeout period
3. Try to access another page
4. Expected: Redirected to login page

## Files Modified for RBAC Implementation

1. **admin/rbac.php** - Central RBAC system with permission matrix and functions
2. **admin/session.php** - Updated to include rbac.php
3. **index.php** - Updated to store user role in session during login
4. **admin/header.php** - Updated to conditionally render menu based on role

## Access Control on Individual Pages

Pages now validated with requireAccess() at the top:

- **students.php** - requireAccess('students', 'view') + delete action check
- **students_add.php** - requireAccess('students', 'add')
- **students_edit.php** - requireAccess('students', 'edit')
- **students_view.php** - requireAccess('students', 'view')
- **teachers.php** - requireAccess('teachers', 'view') + delete action check
- **teachers_add.php** - requireAccess('teachers', 'add')
- **teachers_edit.php** - requireAccess('teachers', 'edit')
- **teachers_view.php** - requireAccess('teachers', 'view')
- **classes.php** - requireAccess('classes', 'view') + delete action check
- **classes_add.php** - requireAccess('classes', 'add')
- **classes_edit.php** - requireAccess('classes', 'edit')
- **attendance.php** - requireAccess('attendance', 'view')
- **exams.php** - requireAccess('exams', 'view') + delete action check
- **exams_add.php** - requireAccess('exams', 'add')
- **exams_edit.php** - requireAccess('exams', 'edit')
- **fees.php** - requireAccess('fees', 'view')
- **users.php** - requireAccess('users', 'view') + delete action check
- **users_add.php** - requireAccess('users', 'add')
- **users_edit.php** - requireAccess('users', 'edit')
- **profile.php** - requireAccess('profile', 'view')
- **settings.php** - requireAccess('settings', 'edit')
- **dashboard.php** - requireAccess('dashboard', 'view')

## RBAC Functions Available

Located in `admin/rbac.php`:

- `hasAccess($module, $action = 'view')` - Check if current user has permission
- `requireAccess($module, $action = 'view')` - Require permission or redirect to dashboard
- `isAdmin()` - Check if user is admin
- `isTeacher()` - Check if user is teacher
- `isStudent()` - Check if user is student
- `isStaff()` - Check if user is NTS
- `getCurrentUserRole()` - Get current user's role
- `getRoleDisplayName($role)` - Get display name for role
- `getRoleColor($role)` - Get color code for role
- `getAvailableModules()` - Get list of available modules for current user
- `showUnauthorizedMessage()` - Display unauthorized message
- `logAccessAttempt($module, $action, $allowed)` - Log access attempts (for audit)

## Expected Access Control Behavior

When an unauthorized user tries to access a restricted page:
1. Page check runs `requireAccess()` function
2. Function checks `$ROLE_PERMISSIONS` matrix
3. If access denied, redirects to admin/dashboard.php
4. Dashboard shows message: "You do not have permission to access this page."

## Notes

- Password is managed through `password_hash()` and `password_verify()` for security
- Session stores: `admin_id`, `admin_username`, `admin_name`, `admin_email`, `admin_role`
- RBAC checks happen at:
  1. Page load time (via requireAccess at top of file)
  2. Action operation time (before delete/edit/add operations)
  3. Menu rendering time (conditional links in header.php)
  4. Button level (via hasAccess() in view code)

## Troubleshooting

### Issue: Access denied when it shouldn't be
- Check role is correctly set in database users table
- Check session is storing admin_role correctly
- Verify rbac.php is included in session.php

### Issue: Menu items showing that shouldn't
- Check header.php has proper hasAccess() conditions
- Clear browser cache and reload
- Check browser console for any JavaScript errors

### Issue: All pages showing access denied
- Check session.php is being included
- Check rbac.php file exists and is accessible
- Check database connection is working

## Next Steps

1. Test all role access scenarios using the test steps above
2. Verify menu items show/hide correctly for each role
3. Test direct URL access attempts
4. Verify button-level access controls work
5. Test form submission access controls
6. Create additional test users for thorough testing
