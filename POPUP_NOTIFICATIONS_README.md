# File Receipt Popup Notification System

## Overview
This feature adds a real-time popup notification system that appears when logged-in users have pending file receipts. Recipients can view, confirm, or dismiss notifications directly from the popup.

## Features

### 1. **Automatic Popup Display**
- Popup automatically appears when a user logs in and has pending file receipts
- Shows at the bottom-right corner of the screen
- Displays a badge with the count of pending receipts

### 2. **Notification Details**
Each notification shows:
- File title
- File number
- Sender name
- Time sent (relative time, e.g., "2 hours ago")
- Priority indicators (for urgent/very urgent files)
- Quick action buttons

### 3. **User Actions**

#### **Confirm Receipt**
- Click "Confirm" button on any file in the popup
- Opens a detailed confirmation modal with:
  - Complete file information
  - Sender details
  - Sender comments (if any)
  - Optional receiver comments field
- Confirms receipt and updates file status

#### **Dismiss Popup**
- Click the "X" button or "Hide" link to dismiss the popup
- Popup remains hidden during the current session
- A floating notification bell icon appears with the count
- Click the bell icon to show the popup again

#### **View All**
- Click "View All Pending Receipts →" to navigate to the full receipts page

### 4. **Responsive Design**
- Mobile-friendly layout
- Smooth animations and transitions
- Maximum height with scroll for multiple notifications
- Hover effects for better UX

## Files Created/Modified

### New Files

1. **`app/Livewire/Notifications/PendingReceipts.php`**
   - Main Livewire component for popup notifications
   - Handles loading pending receipts
   - Manages popup visibility
   - Processes receipt confirmations

2. **`resources/views/livewire/notifications/pending-receipts.blade.php`**
   - Blade template for the popup UI
   - Includes notification list and confirmation modal
   - Floating bell icon when dismissed

3. **`database/migrations/2026_02_06_062925_create_notification_preferences_table.php`**
   - Optional: Stores user notification preferences
   - Fields:
     - `show_popup_notifications` - Enable/disable popups
     - `auto_hide_after_view` - Auto-hide after viewing
     - `popup_position` - Position preference

### Modified Files

1. **`resources/views/layouts/app.blade.php`**
   - Added `<livewire:notifications.pending-receipts />` component
   - Only shows for authenticated users

2. **`resources/views/components/application-logo.blade.php`**
   - Fixed logo overflow issue in header
   - Added responsive sizing classes

## Installation Steps

### 1. Run Database Migration
```bash
php artisan migrate
```

### 2. Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### 3. Test the Feature
1. Log in as a user with pending file receipts
2. The popup should appear automatically
3. Test all actions (confirm, dismiss, show again)

## Usage

### For Recipients

1. **When Logging In:**
   - If you have pending file receipts, a popup will appear automatically
   - The popup shows all files waiting for your confirmation

2. **To Confirm Receipt:**
   - Click the "Confirm" button on any file
   - Review the file details in the modal
   - Add optional comments
   - Click "Confirm Receipt" to finalize

3. **To Dismiss the Popup:**
   - Click the "X" button or "Hide" link
   - The popup will hide but can be reopened
   - A bell icon with a count badge appears

4. **To Show Popup Again:**
   - Click the floating bell icon
   - The popup will reappear with all pending receipts

### For Administrators

The notification system works automatically. No configuration needed.

### Optional: Customize Notification Preferences

To add user preferences (future enhancement):

1. Create a settings page for users
2. Allow users to toggle:
   - Enable/disable popup notifications
   - Auto-hide behavior
   - Popup position preference
3. Store preferences in `notification_preferences` table

## Technical Details

### Component Structure

```
PendingReceipts Component
├── Properties
│   ├── $pendingReceipts (Collection)
│   ├── $showPopup (Boolean)
│   ├── $selectedMovement (FileMovement)
│   ├── $receiverComments (String)
│   └── $dismissed (Boolean)
│
├── Methods
│   ├── mount() - Initialize component
│   ├── loadPendingReceipts() - Fetch pending receipts
│   ├── dismissPopup() - Hide the popup
│   ├── showPopupAgain() - Show popup again
│   ├── selectMovement($id) - Select a file for confirmation
│   ├── confirmReceipt() - Process receipt confirmation
│   └── closeConfirmation() - Close confirmation modal
│
└── Listeners
    └── refreshPendingReceipts - Reload pending receipts
```

### Database Schema

#### notification_preferences table
```sql
id                        (bigint, primary key)
employee_number           (string, foreign key → employees)
show_popup_notifications  (boolean, default: true)
auto_hide_after_view      (boolean, default: false)
popup_position           (integer, default: 1)
created_at               (timestamp)
updated_at               (timestamp)
```

### Styling

The component uses Tailwind CSS classes:
- **Colors:** Blue theme for primary actions
- **Animations:** Smooth slide-up transitions
- **Shadows:** Elevated shadow for popup
- **Responsive:** Mobile-first design

## Future Enhancements

### 1. Real-Time Updates
- Implement WebSocket or Pusher for live notifications
- Update popup count without page refresh
- Show new notifications as they arrive

### 2. Sound Notifications
- Add optional sound when new file arrives
- Configurable in user preferences

### 3. Browser Notifications
- Request permission for browser notifications
- Show desktop notifications when tab is inactive

### 4. Email Notifications
- Send email when file is sent
- Daily digest of pending receipts

### 5. SMS Notifications
- For urgent/very urgent files
- Configurable in user preferences

### 6. Notification History
- Store dismissed notifications
- Allow users to view notification history
- Mark as read/unread

### 7. Bulk Actions
- Confirm multiple receipts at once
- Bulk dismiss notifications

## Troubleshooting

### Popup Not Appearing

**Check:**
1. User is authenticated (`@auth` directive)
2. User has pending file receipts (movement_status = 'sent')
3. Component is loaded in `app.blade.php`
4. Browser console for JavaScript errors

### Styling Issues

**Check:**
1. Tailwind CSS is compiled (`npm run dev` or `npm run build`)
2. Alpine.js is loaded (included in Livewire)
3. No CSS conflicts with other styles

### Confirmation Not Working

**Check:**
1. User is the intended receiver
2. Database connection is active
3. FileMovement model relationships are correct
4. Audit logging is not causing errors

## Security Considerations

1. **Authorization:** Users can only confirm files intended for them
2. **Validation:** All inputs are validated before processing
3. **CSRF Protection:** Livewire handles CSRF automatically
4. **SQL Injection:** Using Eloquent ORM prevents SQL injection
5. **XSS Protection:** Blade templates escape output by default

## Performance

- **Lazy Loading:** Component loads only for authenticated users
- **Pagination:** Not needed in popup (limited to active items)
- **Caching:** Consider caching pending counts for dashboard
- **Database Queries:** Optimized with eager loading (with() method)

## Support

For issues or questions:
1. Check the troubleshooting section above
2. Review the code comments in component files
3. Check Laravel logs: `storage/logs/laravel.log`
4. Test with browser developer tools console

## License

This feature is part of the File Tracking Management System.
