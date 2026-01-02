# Facility Management Module

A Laravel-based **Facility Management Module** developed as part of an **Event Management System**.  
This module provides centralized facility records, booking management, real-time availability checking, and secure administrative control to prevent scheduling conflicts and misuse of shared facilities.

---

## 📌 Overview

The Facility Management Module handles the full lifecycle of campus facility usage:
- Facility creation and maintenance
- Timetable-based availability visualization
- Booking request management with approval workflow
- Secure access control
- RESTful API exposure for cross-module integration

It is designed for **shared-resource environments** such as universities, where multiple societies compete for limited facilities.

---

## ✨ Key Features

### 🏢 Facility Records (Admin)
- Create, edit, deactivate facilities
- Auto-generate venues using prefixes (e.g. C401, C402)
- Soft delete to preserve booking history

### 📅 Facility Timetable & Availability
- Daily timetable view (08:00–22:00, 30-minute slots)
- Status indicators: `Available`, `Booked`, `Inactive`
- Prevents overlapping bookings visually and logically

### 📋 Facility Booking Management
- View and filter bookings by date
- Create manual bookings (Admin)
- Edit bookings with conflict rechecking
- Approve / Reject workflow with audit details

### 🔐 Secure Access Control
- Role-based authorization (Admin vs User)
- Protection Proxy behavior via controllers
- Prevents booking abuse and privilege escalation

### 🔌 Cross-Module Integration
- RESTful API for real-time availability checking
- Consumed by the Event Management Module before event submission

---

## 🧠 Architecture & Design

### MVC Architecture
- **Model**: Eloquent ORM models (`Facility`, `Venue`, `FacilityBooking`)
- **View**: Blade templates for admin UI
- **Controller**: Request handling, validation, authorization, API responses

### Design Pattern
- **Proxy Pattern (Protection Proxy)**
- Controllers act as gatekeepers to validate permissions before executing sensitive operations

---

## 🔒 Secure Coding Practices

### Threats Mitigated
1. **Broken Access Control (BOLA / Privilege Escalation)**
   - Prevents unauthorized booking creation or approval
2. **Malicious File Upload**
   - Prevents disguised executable files via strict validation

### Security Measures
- Laravel FormRequest validation
- Role-based access enforcement
- File type, size, MIME & header checking
- Soft deletion for data integrity

---

## 🔌 Web Service (API)

### Exposed Service
**Check Facility Availability**

- **Endpoint:**  
  `POST /api/facilities/availability`

- **Purpose:**  
  Allows the Event Management Module to verify facility availability before creating or submitting events.

### Request Parameters
```json
{
  "requestID": "UUID",
  "facilityId": 4,
  "startAt": "2025-12-28 10:00:00",
  "endAt": "2025-12-28 12:00:00",
  "timeStamp": "2025-12-28 09:30:00"
}
