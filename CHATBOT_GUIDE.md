# Chatbot Integration Guide

## Overview
This guide explains how to use the integrated chatbot system that has been added to your school management system.

## What's New

### 1. **Database Tables**
Two new tables have been created:
- `chatbot_faqs` - Stores FAQ questions, answers, keywords, and metadata
- `chatbot_conversations` - Logs all user-chatbot conversations for analysis

### 2. **Admin Pages**
New admin panel pages for managing the chatbot:
- `/admin/chatbot.php` - View all FAQs
- `/admin/chatbot_add.php` - Add new FAQ
- `/admin/chatbot_edit.php` - Edit existing FAQ
- `/admin/chatbot_delete.php` - Delete FAQ

### 3. **Chatbot API**
- `/chatbot_api.php` - Backend API that processes user messages and returns intelligent responses

### 4. **Frontend Widget**
- Floating chat widget on the website that users can interact with
- Automatic response generation based on FAQ keywords and content

---

## Setup Instructions

### Step 1: Execute Database Script
```sql
1. Open phpMyAdmin
2. Select your database (school_managements)
3. Go to SQL tab
4. Copy contents of `chatbot_database.sql`
5. Click Execute
```

This creates two tables and inserts 6 sample FAQs.

### Step 2: Verify Admin Access
The following roles have access to chatbot management:
- **Admin**: Full access (view, add, edit, delete)
- **NTS (Non-Teaching Staff)**: Can add/edit FAQs but not delete
- **Teachers**: Can view FAQs only (for reference)
- **Students**: Can view FAQs only

### Step 3: Manage FAQs
Go to Admin Panel → 🤖 Chatbot → FAQs

#### Adding a FAQ:
1. Click "+ Add FAQ"
2. Enter the question (what users might ask)
3. Enter the detailed answer
4. Add keywords (comma-separated, e.g., "admission, apply, enrollment")
5. Select category (general, admission, fees, etc.)
6. Set priority (higher number = shown first in searches)
7. Choose status (active/inactive)
8. Click "Add FAQ"

#### Editing a FAQ:
1. Click "Edit" on any FAQ
2. Modify any fields
3. Click "Update FAQ"

#### Deleting a FAQ:
1. Click "Delete" on any FAQ
2. Confirm deletion

#### Categories Available:
- General
- Admission
- Fees
- Attendance
- Academics
- Transport
- Contact
- Other

---

## Chatbot Widget on Frontend

### Features:
- **Floating Button**: 🤖 button appears in bottom-right corner
- **Easy Interaction**: Click to open, type question, press Enter or click Send
- **Smart Matching**: Chatbot matches user questions with FAQs using keyword analysis
- **Confidence Scores**: Shows how confident the chatbot is about its response
- **Session Tracking**: Maintains conversation history
- **Typing Indicator**: Shows when chatbot is "thinking"

### How It Works:
1. User types a question
2. System searches through all active FAQs
3. Performs keyword matching against:
   - FAQ questions
   - FAQ keywords
   - Individual words in FAQ content
4. Returns the best matching answer
5. Shows confidence level (Low/Medium/High)

### Confidence Levels:
- **✓ High match** (85%+): Very confident in the answer
- **~ Medium match** (70-84%): Fairly confident
- **Low confidence** (<70%): May want to contact support

---

## Sample FAQs Included

The system comes with 6 sample FAQs:

1. **What are the school timings?**
   - Answer: 8:00 AM to 3:30 PM
   - Keywords: timings, hours, school time, opening

2. **How do I apply for admission?**
   - Answer: Apply via admission office or online form
   - Keywords: admission, apply, enrollment, registration

3. **What is the fee structure?**
   - Answer: Varies by class, contact finance office
   - Keywords: fees, tuition, charges, cost, payment

4. **How can I check my child's attendance?**
   - Answer: Use parent portal login
   - Keywords: attendance, absent, present, check, marks

5. **What are the transport facilities?**
   - Answer: Transport available covering major areas
   - Keywords: transport, bus, vehicle, pick up, drop

6. **How do I contact the school?**
   - Answer: Phone, email, and address provided
   - Keywords: contact, phone, email, address, call

---

## Best Practices

### 1. **FAQ Creation**
- Write clear, direct answers
- Use simple language
- Include helpful keywords
- Keep answers reasonably concise
- Use bullet points for complex answers

### 2. **Keyword Strategy**
- Think like a user - what would they type?
- Include synonyms (e.g., "timings, hours, schedule")
- Include abbreviations if applicable
- Separate keywords with commas

### 3. **Category Usage**
- Organize FAQs by category for better management
- Set higher priority for frequently asked questions
- Inactive FAQs won't appear in chatbot responses

### 4. **Regular Updates**
- Review conversations regularly
- Update FAQs based on common questions
- Add new FAQs for repeated questions
- Remove or update outdated information

---

## For Developers

### Chatbot Algorithm
The matching algorithm uses three-tier scoring:

1. **Question Match** (100 points)
   - Exact or close phrase match with user input

2. **Keyword Match** (50 points each)
   - Match against FAQ keywords (comma-separated)

3. **Word Match** (10 points each)
   - Match individual words (3+ characters only)
   - Fuzzy matching for similar words

**Confidence Score Calculation:**
- Score >= 100: 95% confidence
- Score >= 50: 80% confidence  
- Score >= 10: 60% confidence
- Score < 10: Shows "not sure" message

### API Response Format
```json
{
  "success": true,
  "message": "The chatbot response",
  "session_id": "session_abc123",
  "confidence": 85,
  "faq_id": 5
}
```

### Logging
All conversations are logged in `chatbot_conversations` table including:
- User message
- Bot response
- Matching FAQ ID
- Confidence score
- Session ID for conversation tracking
- User IP address
- Timestamp

---

## Troubleshooting

### Issue: Chatbot widget not appearing
**Solution:**
- Check that `chatbot_database.sql` has been executed
- Verify `chatbot_api.php` is in root directory
- Check browser console for JavaScript errors
- Clear browser cache and reload

### Issue: Chatbot giving wrong answers
**Solution:**
- Review and update FAQ keywords
- Check category assignments
- Adjust priority of better FAQs
- Add more specific FAQs with better keywords
- Mark unhelpful FAQs as inactive

### Issue: Conversations not being logged
**Solution:**
- Verify `chatbot_conversations` table exists
- Check database write permissions
- Verify `chatbot_api.php` is receiving POST requests
- Check server error logs

---

## Customization

### To change the chatbot button position:
Edit `assets/css/chatbot.css` - `.chatbot-widget` class:
```css
bottom: 20px;  /* Distance from bottom */
right: 20px;   /* Distance from right */
```

### To change colors:
Update gradient colors in:
- `.chatbot-toggle`
- `.chatbot-header`
- `.user .message-content`

### To change initial greeting:
Edit `assets/js/chatbot.js` - search for "Hi! 👋" message

---

## Security Notes

1. **Input Sanitization**: User messages are HTML-escaped
2. **SQL Prepared**: Queries use proper escaping
3. **RBAC Enforcement**: Only authorized users can manage FAQs
4. **Session Tracking**: Conversations logged with IP and session ID
5. **Status Control**: Only active FAQs are shown to users

---

## Support

If you encounter issues:
1. Check the troubleshooting section above
2. Review browser console (F12 → Console tab)
3. Check server error logs
4. Verify database tables exist and have data
5. Test with sample FAQs first

---

**Version:** 1.0
**Last Updated:** 2024
