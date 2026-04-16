# Content Management, Gallery & Events System

## 📋 Overview

This guide covers the complete content management system including:
- **Content Management** - Publish news and announcements
- **Gallery Management** - Upload and manage school photos
- **Events Management** - Create and manage school events

All content is managed from the admin panel and automatically displayed on the frontend website.

---

## 🗄️ Database Setup

### Step 1: Run SQL to Create Tables

Execute the following SQL to create the necessary database tables:

```sql
-- File: database_additions.sql
-- Located in: school_mangment/ root directory
```

Or manually run in phpMyAdmin:

1. Open phpMyAdmin
2. Select your database: `school_managements`
3. Go to SQL tab
4. Copy and paste contents from `database_additions.sql`
5. Click Execute

### Tables Created:
- `content` - For blog posts, news, announcements
- `gallery` - For school photos
- `gallery_categories` - Gallery categories (optional)
- `events` - For school events
- `event_categories` - Event categories (optional)

---

## 📝 CONTENT MANAGEMENT

### Access Admin Panel

1. Open: `http://localhost/school_mangment/admin/dashboard.php`
2. Login with admin credentials
3. Click **Content** menu → **All Content**

### Create Content

#### Step 1: Click "Add Content"
- URL: `admin/content_add.php`

#### Step 2: Fill Form

| Field | Required | Notes |
|-------|----------|-------|
| Title | ✅ | Main headline |
| Slug | ❌ | URL-friendly version (auto-generated) |
| Description | ❌ | Short preview text |
| Featured Image | ❌ | Header image for content |
| Content | ✅ | Main content (supports HTML) |
| Status | ✅ | Draft or Published |

#### Step 3: Save
- Choose status: **Draft** (hidden) or **Published** (visible on frontend)
- Click **Save Content**

### Edit Content

1. Go to **Content** → **All Content**
2. Click **Edit** button on desired content
3. Make changes
4. Click **Save**

### Delete Content

1. Go to **Content** → **All Content**
2. Click **Delete** button
3. Confirm deletion

### Frontend Display

Published content appears on:
- **URL**: `frontend.php#content`
- **Section**: "Latest News" 
- **Shows**: 3 latest published articles
- **Click**: "Read More" to view full content

---

## 🖼️ GALLERY MANAGEMENT

### Access Gallery Manager

1. In Admin Panel: **Gallery** → **View Gallery**
2. URL: `admin/gallery.php`

### Upload Images

#### Step 1: Click "Add Images"
- URL: `admin/gallery_add.php`

#### Step 2: Fill Form

| Field | Required | Notes |
|-------|----------|-------|
| Image Title | ✅ | Display name for image |
| Select Image | ✅ | JPG, PNG, GIF (max 5MB) |
| Category | ❌ | Organize by category |
| Alt Text | ❌ | For accessibility |
| Description | ❌ | Image description |

#### Step 3: Upload
- Choose image from computer
- Image preview shows
- Click **Upload Image**

### Multiple Upload

1. Upload first image
2. Click **Add Another**
3. Repeat until done
4. Click **Back to Gallery**

### Organize Gallery

- Images display by order
- Drag to reorder (if enabled)
- Group by categories

### Delete Images

1. On gallery page, click image
2. Click **Delete** button
3. Confirm deletion
4. File automatically deleted from server

### Frontend Display

Gallery appears on:
- **URL**: `frontend.php#gallery`
- **Section**: "Our Gallery"
- **Grid**: Responsive image gallery
- **Shows**: 12 most recent active images
- **Hover**: See image title and category

---

## 📅 EVENTS MANAGEMENT

### Access Events Manager

1. In Admin Panel: **Events** → **All Events**
2. URL: `admin/events.php`

### Create Event

#### Step 1: Click "Add Event"
- URL: `admin/events_add.php`

#### Step 2: Fill Form

| Field | Required | Notes |
|-------|----------|-------|
| Event Title | ✅ | Event name |
| Start Date | ✅ | Date of event |
| Start Time | ❌ | Time event begins |
| End Date | ❌ | For multi-day events |
| Location | ❌ | Event venue |
| Category | ❌ | Sports, Academic, etc. |
| Description | ❌ | Event details |
| Featured Image | ❌ | Event poster/image |
| Status | ✅ | Upcoming, Ongoing, Completed, Cancelled |

#### Step 3: Save
- Select appropriate status
- Click **Save Event**

### Event Status Guide

| Status | Usage | Displays |
|--------|-------|----------|
| Upcoming | Before event | ✅ Yes |
| Ongoing | During event | ✅ Yes |
| Completed | After event | ❌ Hidden from upcoming |
| Cancelled | Event cancelled | ❌ Hidden |

### Event Categories

Pre-built options:
- **Sports** - Sports events
- **Academic** - Academic programs
- **Cultural** - Cultural activities
- **Celebration** - School celebrations

Or create custom categories.

### Edit Event

1. Go to **Events** → **All Events**
2. Click **Edit** on event
3. Make changes
4. Save

### Delete Event

1. Go to **Events** → **All Events**
2. Click **Delete**
3. Confirm

### Frontend Display

Events appear on:
- **URL**: `frontend.php#events`
- **Section**: "Upcoming Events"
- **Shows**: Next 6 upcoming/ongoing events
- **Format**: Card layout with date, time, location
- **Sort**: By event date (nearest first)

---

## 🌐 Frontend Display

### How Content Appears

#### Content/News Section
```
Latest News
├─ 3 published articles
├─ Shows: Title, date, description
└─ Click: "Read More" (link to full article)
```

#### Gallery Section
```
Our Gallery
├─ 12 latest gallery images
├─ Grid layout (responsive)
├─ On hover: Show image details
└─ Click: Enlarge/view full size
```

#### Events Section
```
Upcoming Events
├─ 6 upcoming/ongoing events
├─ Card layout with:
│  ├─ Date (highlighted box)
│  ├─ Title
│  ├─ Time
│  ├─ Location
│  ├─ Description snippet
│  └─ Category badge
└─ Sorted by date
```

### Frontend Screenshots

See `frontend.php` for complete layout including:
- Navigation with: Home, Gallery, Events, News links
- Smooth scrolling to sections
- Responsive mobile design

---

## 📂 File Locations

### Admin Pages
```
admin/
├─ content.php          → List content
├─ content_add.php      → Add new content
├─ content_edit.php     → Edit content
├─ content_delete.php   → Delete content
├─ gallery.php          → View gallery
├─ gallery_add.php      → Upload images
├─ gallery_edit.php     → Edit gallery item
├─ gallery_delete.php   → Delete image
├─ events.php           → List events
├─ events_add.php       → Add event
├─ events_edit.php      → Edit event
└─ events_delete.php    → Delete event
```

### Upload Directories
```
uploads/
├─ content/             → Content featured images
├─ gallery/             → Gallery images
└─ events/              → Event featured images
```

### Frontend
```
frontend.php            → Main landing page (displays all content)
```

---

## 🎨 Customization

### Change Section Order

Edit `frontend.php` to reorder sections:
1. Move gallery section code
2. Adjust navigation links
3. Update scroll anchors

### Modify Template Style

Edit `assets/css/frontend.css`:

```css
/* Gallery grid columns */
.gallery-grid {
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
}

/* Event card styling */
.event-card {
    /* Modify here */
}

/* Content card styling */
.content-card {
    /* Modify here */
}
```

### Change Number of Displayed Items

Edit `frontend.php`:

```php
// Show 6 gallery items instead of 12
$gallery_list = getAllRows("SELECT * FROM gallery LIMIT 6");

// Show 8 events instead of 6
$events_list = getAllRows("SELECT * FROM events LIMIT 8");

// Show 5 content items instead of 3
$content_list = getAllRows("SELECT * FROM content LIMIT 5");
```

---

## 🔐 Access Control

### Admin Permissions

Set in RBAC system:

```php
// Add these to your user roles:

// Content Management
- content.view       → Can view content list
- content.add        → Can create content
- content.edit       → Can edit content
- content.delete     → Can delete content

// Gallery
- gallery.view       → Can view gallery
- gallery.add        → Can upload images
- gallery.edit       → Can edit gallery items
- gallery.delete     → Can delete images

// Events
- events.view        → Can view events
- events.add         → Can create events
- events.edit        → Can edit events
- events.delete      → Can delete events
```

### Default Access

Currently available to: **Admin only**

To allow other roles:
1. Edit `admin/rbac.php`
2. Add roles to each section
3. Save permissions

---

## 💡 Best Practices

### Content Management
- ✅ Use clear, descriptive titles
- ✅ Write compelling descriptions
- ✅ Use featured images (recommended)
- ✅ Set status to Draft before publishing
- ✅ Review before publishing

### Gallery
- ✅ Optimize images before upload
- ✅ Use descriptive titles
- ✅ Add alt text for accessibility
- ✅ Organize with categories
- ✅ Recommended size: 800x600px+

### Events
- ✅ Include complete details
- ✅ Add accurate date/time
- ✅ Specify location
- ✅ Use event image/poster
- ✅ Update status when event occurs
- ✅ Use consistent categories

---

## 🚀 Tips & Tricks

### Quick Publish Flow
1. Add content with "Draft" status
2. Review on frontend
3. Return to admin
4. Change to "Published"
5. Refresh frontend

### Organize with Categories
- Use categories in gallery and events
- Helps with filtering (can add later)
- Makes site more organized

### Image Best Practices
- **Gallery**: 800x600px or larger
- **Content**: 1200x600px
- **Events**: 1200x600px
- All: Compress before upload (< 2MB)

### Content Scheduling
- Create as "Draft"
- Update status to "Published" when ready
- Currently no auto-scheduling (can add)

---

## ❓ Troubleshooting

### Images Not Uploading
- Check upload folder permissions (should be 755)
- Check image file size (max 5MB)
- Verify image format (JPG, PNG, GIF)
- Run: `mkdir uploads/gallery uploads/content uploads/events`

### Content Not Showing on Frontend
- Check status is **Published** (not Draft)
- Verify database connection
- Check if content table exists
- Confirm no database errors

### Events Not Appearing
- Check event date is correct
- Verify status is **Upcoming** or **Ongoing**
- Check featured image path if not showing
- Limit might be reached (max 6 shown)

### No Gallery Images
- Check if images uploaded
- Confirm status is **active**
- Verify image paths are correct
- Check CSS grid displays correctly

---

## 🔄 Integration with Admin Dashboard

### Dashboard Widgets

Add to dashboard to show:
- Latest 3 pieces of content
- Gallery stats
- Upcoming events count
- Recent uploads

```php
// Add to dashboard.php
$content_count = getRow("SELECT COUNT(*) as count FROM content WHERE status = 'published'");
$gallery_count = getRow("SELECT COUNT(*) as count FROM gallery WHERE status = 'active'");
$events_count = getRow("SELECT COUNT(*) as count FROM events WHERE status = 'upcoming'");
```

---

## 📊 Analytics (Optional)

Add later:
- View count for content
- Download count for gallery
- Event attendance
- Popular categories

---

## 🔗 Quick Links

### Admin URLs
- Dashboard: `/admin/dashboard.php`
- Content: `/admin/content.php`
- Gallery: `/admin/gallery.php`
- Events: `/admin/events.php`

### Frontend
- Main: `frontend.php`
- Content Section: `frontend.php#content`
- Gallery Section: `frontend.php#gallery`
- Events Section: `frontend.php#events`

---

## ✅ Verification Checklist

Ensure everything works:
- [ ] Database tables created
- [ ] Admin menu shows new options
- [ ] Can add content
- [ ] Can upload gallery images
- [ ] Can create events
- [ ] Frontend displays content
- [ ] Frontend displays gallery
- [ ] Frontend displays events
- [ ] Images appear correctly
- [ ] Styling looks good
- [ ] Mobile responsive
- [ ] Delete buttons work

---

**Last Updated**: April 2026  
**Version**: 1.0  
**Status**: Production Ready ✅
