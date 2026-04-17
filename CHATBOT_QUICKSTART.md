# Chatbot Quick Start

## 🚀 Get Started in 3 Steps

### Step 1️⃣ Setup Database (2 minutes)
```
1. Open phpMyAdmin
2. Select: school_managements database  
3. Go to: SQL tab
4. Copy & paste: chatbot_database.sql content
5. Click: Execute
✅ Done! Tables created with 6 sample FAQs
```

### Step 2️⃣ Test on Website
```
1. Open frontend.php in browser
2. Scroll to bottom-right corner
3. Click the 🤖 button
4. Type a question like: "What are timings?"
5. Bot responds with relevant answer
✅ Chatbot is live!
```

### Step 3️⃣ Manage FAQs in Admin
```
1. Login to admin panel
2. Click: 🤖 Chatbot in menu
3. Click: "+ Add FAQ"
4. Add your custom questions & answers
✅ Your FAQ is now active!
```

---

## 💡 Tips

### Adding Your First FAQ
- **Question**: "What is your school name?"
- **Answer**: "School Management System - Your school name here"
- **Keywords**: school, name, information
- **Category**: general
- **Priority**: 5
- **Status**: active

### Testing
1. Add FAQ → Check chatbot on website
2. Try typing similar questions
3. Watch confidence scores
4. Higher score = better match

### Common Questions to Add
- Admission process
- Fee structure  
- School contact info
- Holidays schedule
- Uniform info
- Transport routes
- Admission requirements
- Query procedures

---

## 📊 User Access Levels

| Role | Can View | Can Add | Can Edit | Can Delete |
|------|----------|--------|----------|-----------|
| Admin | ✅ | ✅ | ✅ | ✅ |
| NTS | ✅ | ✅ | ✅ | ❌ |
| Teacher | ✅ | ❌ | ❌ | ❌ |
| Student | ✅ | ❌ | ❌ | ❌ |

---

## 🔧 Files Added

```
📁 New Files:
├── chatbot_database.sql          ← Database tables
├── admin/chatbot.php              ← List FAQs
├── admin/chatbot_add.php           ← Add FAQ
├── admin/chatbot_edit.php          ← Edit FAQ
├── admin/chatbot_delete.php        ← Delete FAQ
├── chatbot_api.php                 ← Chat API
├── assets/css/chatbot.css          ← Styling
├── assets/js/chatbot.js            ← Widget code
├── CHATBOT_GUIDE.md                ← Full guide
└── CHATBOT_QUICKSTART.md           ← This file
```

---

## 🎨 Widget Features

✅ Floating button (bottom-right)
✅ Smooth animations
✅ Typing indicator
✅ Confidence scores
✅ Message timestamps
✅ Mobile responsive
✅ Session tracking
✅ Dark mode messages

---

## ⚙️ FAQ Fields Explained

| Field | What It Does |
|-------|-------------|
| **Question** | What users might ask |
| **Answer** | Your detailed response |
| **Keywords** | Search terms (comma-separated) |
| **Category** | Organize FAQs (admission, fees, etc) |
| **Priority** | Higher = shown first (1-100) |
| **Status** | Active/Inactive (inactive won't show) |

---

## 🔍 How Matching Works

```
User asks: "How do I apply?"
                    ↓
System searches FAQs for:
- Exact phrase matches
- Keyword matches (from FAQ keywords field)
- Individual word matches
                    ↓
Calculates confidence score:
- High match: 85%+ confidence
- Medium: 70-84% confidence
- Low: <70% confidence
                    ↓
Returns best matching answer
```

---

## 📈 Sample FAQ Template

```
Question: What is the admission deadline?
Answer: The admission deadline for the current academic year is June 30th. 
Please contact our admission office for extension requests.

Keywords: admission, deadline, apply, enrollment, timeline

Category: admission
Priority: 8 (high - frequently asked)
Status: active
```

---

## ✉️ Support & Troubleshooting

**Chatbot not appearing?**
- Clear browser cache (Ctrl+Shift+Del)
- Refresh page (Ctrl+F5)
- Check JavaScript console (F12)

**Wrong answers?**
- Review FAQ keywords
- Adjust priority/status
- Add more specific FAQs
- Test with sample questions first

**Conversations not logging?**
- Verify database tables exist
- Check database permissions
- Check `chatbot_api.php` is accessible

---

**✨ Your chatbot is ready! Start with the 6 sample FAQs, then customize with your school-specific questions.**
