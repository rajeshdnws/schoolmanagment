# RBAC Developer Quick Reference

## Quick Function Reference

### Permission Checking Functions

```php
// Check if user has permission (returns boolean)
if (hasAccess('students', 'view')) {
    // User can view students
}

// Require permission (redirects if denied)
requireAccess('students', 'view'); // At top of page

// Role checking
if (isAdmin()) { }           // Is user admin?
if (isTeacher()) { }         // Is user teacher?
if (isStudent()) { }         // Is user student?
if (isStaff()) { }           // Is user NTS?
```

### Helper Functions

```php
// Get current user's role
$role = getCurrentUserRole(); // Returns: 'admin', 'teacher', 'student', or 'nts'

// Get role display name
$display = getRoleDisplayName($_SESSION['admin_role']);
// Returns: "Administrator", "Teacher", "Student", "Non-Teaching Staff"

// Get role color (for styling)
$color = getRoleColor($_SESSION['admin_role']); 
// Returns: '#d9534f', '#0275d8', '#28a745', '#5bc0de'

// Get available modules for current user
$modules = getAvailableModules();
// Returns: array of module names user can access

// Show unauthorized message
showUnauthorizedMessage();
// Displays: "You do not have permission to access this page."

// Log access attempt (for audit)
logAccessAttempt('students', 'delete', false); // module, action, allowed?
```

## Common Usage Patterns

### Pattern 1: Protect an Entire Page
```php
<?php
include('session.php');
requireAccess('students', 'view');

// Rest of page only loads if user has access
$page_title = 'Students Management';
include('header.php');
?>
...
```

### Pattern 2: Protect a Specific Action
```php
// Handle delete
if (isset($_GET['delete'])) {
    requireAccess('students', 'delete');  // Check before action
    $id = intval($_GET['delete']);
    deleteData('students', "id = $id");
    header("Location: students.php?msg=deleted");
    exit();
}
```

### Pattern 3: Conditionally Show Button
```php
<?php if (hasAccess('students', 'add')): ?>
    <a href="students_add.php" class="btn-primary">+ Add New Student</a>
<?php endif; ?>
```

### Pattern 4: Conditionally Show Edit/Delete in Loop
```php
<?php foreach ($students as $student): ?>
    <tr>
        <td><?php echo $student['name']; ?></td>
        <td>
            <?php if (hasAccess('students', 'edit')): ?>
                <a href="students_edit.php?id=<?php echo $student['id']; ?>">Edit</a>
            <?php endif; ?>
            <?php if (hasAccess('students', 'delete')): ?>
                <a href="students.php?delete=<?php echo $student['id']; ?>">Delete</a>
            <?php endif; ?>
        </td>
    </tr>
<?php endforeach; ?>
```

### Pattern 5: Role-Based Menu Item
```php
<?php if (hasAccess('students')): ?>
    <li>
        <a href="students.php">Students</a>
    </li>
<?php endif; ?>
```

### Pattern 6: Admin-Only Content
```php
<?php if (isAdmin()): ?>
    <div class="admin-panel">
        <h3>Admin Tools</h3>
        <!-- Admin-only content -->
    </div>
<?php endif; ?>
```

## Modifying Permissions

### Changing a Permission

Edit `admin/rbac.php` Permission Matrix:

```php
$ROLE_PERMISSIONS = array(
    'admin' => array(
        'dashboard' => true,
        'students' => array(
            'view' => true,
            'add' => true,
            'edit' => true,              // ← Change this
            'delete' => true
        ),
        // ...
    ),
);
```

Changes take effect on next page load (no deployment needed).

### Adding a New Module to Existing Role

In `$ROLE_PERMISSIONS`, add the module to a role:

```php
'teacher' => array(
    // ... existing permissions ...
    'new_module' => array('view' => true, 'add' => false, 'edit' => false, 'delete' => false),
),
```

Then protect pages:
```php
requireAccess('new_module', 'view');
```

### Creating a New Role

1. Add to `$ROLE_PERMISSIONS`:
```php
'supervisor' => array(
    'dashboard' => true,
    'students' => array('view' => true, 'add' => false, 'edit' => false, 'delete' => false),
    'teachers' => array('view' => true, 'add' => false, 'edit' => false, 'delete' => false),
    'profile' => true,
    'settings' => true,
),
```

2. Update database users table to allow role:
```sql
UPDATE users SET role = 'supervisor' WHERE id = 123;
```

3. Test with user having that role

### Extending hasAccess() for Custom Logic

If you need custom rules (e.g., teacher only sees their class):

```php
// Option 1: Extend in rbac.php
function hasAccessToStudent($student_id) {
    $role = getCurrentUserRole();
    $user_id = $_SESSION['admin_id'];
    
    if ($role === 'admin') return true;
    if ($role === 'teacher') {
        // Check if student is in teacher's class
        $result = getRow("
            SELECT s.* FROM students s
            JOIN classes c ON s.class_id = c.id
            WHERE c.class_teacher_id = $user_id AND s.id = $student_id
        ");
        return $result ? true : false;
    }
    return false;
}

// Usage
if (hasAccessToStudent(5)) {
    // Can access
}
```

## Adding RBAC to New Pages

### Step 1: Protect the Page
```php
<?php
include('session.php');
requireAccess('new_module', 'view');
?>
```

### Step 2: Add to Permission Matrix
If the module doesn't exist, add it to `$ROLE_PERMISSIONS` in `rbac.php`:
```php
'new_module' => array('view' => true, 'add' => false, 'edit' => false, 'delete' => false),
```

### Step 3: Protect Actions (if applicable)
```php
if (isset($_POST['add_action'])) {
    requireAccess('new_module', 'add');
    // Add logic
}

if (isset($_GET['delete'])) {
    requireAccess('new_module', 'delete');
    // Delete logic
}
```

### Step 4: Show/Hide Buttons
```php
<?php if (hasAccess('new_module', 'add')): ?>
    <a href="new_module_add.php">Add</a>
<?php endif; ?>
```

### Step 5: Test with Each Role
- Login as admin - should have access
- Login as other roles - should see appropriate restrictions

## Debugging RBAC Issues

### Issue: Access denied when it shouldn't be

1. Check user role in database:
```sql
SELECT id, username, role FROM users WHERE id = 1;
```

2. Check session has role:
```php
echo $_SESSION['admin_role']; // Should output role
```

3. Check permission matrix:
```php
// Add debug to rbac.php
var_dump($ROLE_PERMISSIONS[$role]); // See role's permissions
```

4. Check requireAccess call:
```php
// Make sure it's called after session include
// Order must be: include('session.php'); then requireAccess();
```

### Issue: Menu items showing incorrectly

1. Verify header.php has hasAccess() checks:
```php
<?php if (hasAccess('students')): ?>  // Should wrap menu item
    <li><a href="students.php">Students</a></li>
<?php endif; ?>
```

2. Check rbac.php is included in session.php:
```php
// In session.php, after other includes:
include('rbac.php');
```

### Issue: All pages showing access denied

1. Check database connection works
2. Check users table has 'role' field
3. Check rbac.php file exists and is readable
4. Check admin/session.php includes rbac.php

### Quick Debug Helper
Add to any page to debug:
```php
<?php
echo "Role: " . getCurrentUserRole() . "<br>";
echo "hasAccess('students', 'view'): " . (hasAccess('students', 'view') ? 'YES' : 'NO') . "<br>";
echo "isAdmin(): " . (isAdmin() ? 'YES' : 'NO') . "<br>";
echo "<pre>";
var_dump($ROLE_PERMISSIONS[$_SESSION['admin_role']]);
echo "</pre>";
?>
```

## Performance Tips

1. **Permission checks are fast** - Array lookups are O(1), no database queries
2. **Cache permission matrix** - Loaded once per session (no overhead)
3. **Button display** - Uses hasAccess() which is very lightweight
4. **No N+1 queries** - RBAC doesn't cause extra database queries

## Best Practices

1. **Always check at page load time** using `requireAccess()`
   ```php
   <?php include('session.php'); requireAccess('module', 'view'); ?>
   ```

2. **Always check before actions** (delete, edit, add)
   ```php
   if (isset($_GET['delete'])) {
       requireAccess('module', 'delete');
       // ... delete ...
   }
   ```

3. **Use hasAccess() for UI only** - Don't rely on it for security
   ```php
   // This hides button - good for UX
   <?php if (hasAccess('students', 'delete')): ?>
       <a href="delete.php">Delete</a>
   <?php endif; ?>
   
   // But also protect the delete.php page
   // (requireAccess() in delete.php)
   ```

4. **Keep permission matrix simple** - Don't add too many custom fields
   ```php
   // GOOD - clear structure
   'students' => array('view' => true, 'add' => true, 'edit' => true, 'delete' => true)
   
   // BAD - too complex
   'students' => array('view_own' => true, 'view_all' => false, 'add_flag' => true, ...)
   ```

5. **Document permission changes** - Note why permission changed
   ```php
   // Teacher can no longer delete exams (requested by admin)
   'exams' => array('view' => true, 'add' => true, 'edit' => true, 'delete' => false),
   ```

## Useful SQL Queries

```sql
-- See all users and their roles
SELECT id, username, first_name, last_name, role, status FROM users ORDER BY role;

-- Find all admins
SELECT * FROM users WHERE role = 'admin';

-- Find all teachers
SELECT * FROM users WHERE role = 'teacher';

-- Count users by role
SELECT role, COUNT(*) FROM users GROUP BY role;

-- Change user's role
UPDATE users SET role = 'teacher' WHERE id = 5;

-- Make user admin
UPDATE users SET role = 'admin' WHERE username = 'john_doe';
```

## File Locations

| File | Purpose |
|------|---------|
| `admin/rbac.php` | Core RBAC system - **Don't move this** |
| `admin/session.php` | Includes rbac.php - **Must include RBAC** |
| `index.php` | Sets admin_role in session during login |
| `admin/header.php` | Has hasAccess() checks for menu items |
| All admin pages | Have requireAccess() at top |

## Testing Commands (PHP CLI)

```bash
# Test database connection
php -r "
include('db_config.php');
\$result = \$conn->query('SELECT COUNT(*) FROM users');
echo 'Users: ' . \$result->fetch_row()[0];
"

# Test RBAC functions
php -r "
\$_SESSION['admin_role'] = 'teacher';
\$_SESSION['admin_id'] = 1;
include('admin/rbac.php');
var_dump(hasAccess('students', 'add'));
"
```

## Common Issues & Solutions

| Issue | Cause | Solution |
|-------|-------|----------|
| Access denied for everyone | RBAC not included | Add `include('rbac.php')` to session.php |
| Menu items show for all roles | hasAccess() not used | Wrap menu items with `<?php if (hasAccess()): ?>` |
| Delete still works without permission | No requireAccess() check | Add `requireAccess('module', 'delete')` before delete operation |
| Role not in session | Not set during login | Add `$_SESSION['admin_role'] = $admin['role'];` in index.php |
| Buttons show but access denied | No page-level check | Add `requireAccess()` at top of page |

---

**Last Updated**: After complete RBAC implementation  
**Version**: 1.0  
**Status**: Production Ready ✓
