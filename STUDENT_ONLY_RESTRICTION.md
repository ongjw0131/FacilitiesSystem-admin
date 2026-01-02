# Student-Only Routes Restriction

## Overview
Implemented role-based route restrictions to prevent admins from accessing student-only pages and functionality.

---

## What Changed

### 1. New Middleware: `StudentOnly`
**File**: `app/Http/Middleware/StudentOnly.php`

```php
class StudentOnly {
    // Checks user role and denies access if user is admin
    // Returns 403 error: "Admins cannot access this page. This area is for students only."
}
```

**How it works:**
- Verifies user is authenticated
- Checks if user role is 'admin'
- If admin: Aborts with 403 Forbidden
- If student: Allows access

---

## Routes Protected with `student.only` Middleware

### 1. **Profile & Notification Routes** (Student-only)
```
GET  /profile                          (View profile)
GET  /profile/settings                 (View settings)
POST /profile/update                   (Update profile)
GET  /notifications                    (View notifications)
DELETE /notifications/{id}             (Delete notification)
DELETE /notifications/clear-all        (Clear all notifications)
GET  /president/events                 (President events)
```

**Status**: ✅ Admin cannot access
- Admins trying to access `/profile` will get 403 error
- Only students can view/manage their profiles and notifications

---

### 2. **Society Student-Only Routes**
```
GET  /society/joined                   (View joined societies)
GET  /society/create                   (Create society form)
POST /society                          (Store new society)
POST /society/{id}/join                (Join society)
POST /society/{id}/direct-join         (Direct join society)
POST /society/{id}/request-join        (Request join society)
POST /society/{id}/member/leave        (Leave society)
POST /society/{id}/follow              (Follow society)
POST /society/{id}/unfollow            (Unfollow society)
GET  /society/{id}/is-following        (Check following status)
```

**Status**: ✅ Admin cannot access
- Admins trying to join/follow societies will get 403 error
- Only students can join, follow, and leave societies

---

### 3. **Ticket Purchase Routes** (Student-only)
```
GET  /events/{event}/buy-tickets       (View tickets for purchase)
GET  /tickets/{ticket}/quantity        (Select quantity)
POST /ticket-orders                    (Create order)
POST /ticket-orders/checkout           (Process checkout)
GET  /ticket-orders/{order}/success    (Payment success)
```

**Status**: ✅ Admin cannot access
- Admins cannot purchase tickets from student interface
- Admin tickets are managed separately in admin panel

---

### 4. **Event Creation Routes** (Student-only)
```
GET  /events/create                    (Create event form)
POST /events                           (Store event)
```

**Status**: ✅ Admin cannot access
- Only students can create events from user interface
- Admins create events from admin panel instead

---

### 5. **Event Management Routes** (Student-only)
```
GET  /events/{event}/edit              (Edit event)
PUT  /events/{event}                   (Update event)
DELETE /events/{event}                 (Delete event)
POST /events/{event}/join              (Join event)
```

**Status**: ✅ Admin cannot access
- Students can edit/delete their own events
- Admins cannot use student-facing event management

---

## Routes Available to Both Admins & Students

These routes are kept with just `auth` middleware (no `student.only`):

```
GET  /society                          (View all societies - public)
GET  /society/{id}                     (View society details - public)
GET  /society/{id}/edit                (Edit society - both roles)
GET  /society/{id}/settings            (Society settings - both roles)
POST /society/{id}/settings            (Update settings - both roles)
GET  /society/{id}/people              (View members - both roles)
POST /society/{id}/member/{id}/promote (Manage members - both roles)
POST /society/{id}/member/{id}/pass-president (Pass role - both roles)
POST /society/{id}/member/{id}/downgrade (Downgrade - both roles)
POST /society/{id}/member/{id}/kick    (Kick member - both roles)
```

These allow both admins and students to perform management operations when needed.

---

## Routes Exclusive to Admins (with role check)

```
GET  /user/admin                       (Admin dashboard)
GET  /user/admin_user                  (User management)
GET  /user/admin/society               (Society management)
GET  /user/admin/event                 (Event oversight)
GET  /user/admin/report                (Reports & analytics)
GET  /user/admin/settings              (System settings)
POST /user/create-admin                (Create admin user)
DELETE /user/{id}/delete               (Delete user)
GET  /admin/facilities                 (Facility management)
GET  /admin/bookings                   (Booking management)
GET  /events/admin                     (Admin event list)
GET  /events/{id}/admin                (Admin event details)
```

**Status**: ✅ Only admins can access (403 error for students)

---

## Error Handling

When an admin tries to access a student-only route:

**HTTP Response**:
- **Status Code**: 403 Forbidden
- **Error Message**: "Admins cannot access this page. This area is for students only."

Example:
```
Admin tries to access: GET /profile
Response: 403 Forbidden
Message: "Admins cannot access this page. This area is for students only."
```

---

## Benefits

✅ **Clear Role Separation** - Admin and student interfaces are completely separate
✅ **Prevention of Privilege Confusion** - Admins can't accidentally use student features
✅ **Security** - Prevents admins from interfering with student-specific data
✅ **Data Integrity** - Admins can't create/join societies as students
✅ **Audit Trail** - All role-based access is logged and controlled
✅ **User Experience** - Navigation and features only show relevant options per role

---

## Summary of Protection

| Feature | Student Access | Admin Access |
|---------|---|---|
| View Profile | ✅ Allowed | ❌ Blocked |
| Manage Profile | ✅ Allowed | ❌ Blocked |
| View Notifications | ✅ Allowed | ❌ Blocked |
| Join Society | ✅ Allowed | ❌ Blocked |
| Follow Society | ✅ Allowed | ❌ Blocked |
| Create Event | ✅ Allowed | ❌ Blocked |
| Purchase Tickets | ✅ Allowed | ❌ Blocked |
| Admin Dashboard | ❌ Blocked | ✅ Allowed |
| Manage Users | ❌ Blocked | ✅ Allowed |
| Manage Facilities | ❌ Blocked | ✅ Allowed |

---

## Testing

To test the student-only restriction:

1. **Login as Admin** and try to access:
   - `GET /profile` → Should get 403 Forbidden
   - `GET /society/joined` → Should get 403 Forbidden
   - `GET /events/create` → Should get 403 Forbidden
   - `POST /events/{event}/join` → Should get 403 Forbidden

2. **Login as Student** and try to access:
   - `GET /profile` → Should work (200 OK)
   - `GET /society/joined` → Should work (200 OK)
   - `GET /events/create` → Should work (200 OK)
   - `POST /events/{event}/join` → Should work

3. **Check Admin Routes** (Students should be blocked):
   - `GET /user/admin` → Should get 403 (requires admin role check)
   - `POST /user/create-admin` → Should get 403 (requires admin role check)
