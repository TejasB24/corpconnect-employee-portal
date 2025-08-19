# CorpConnect Employee Portal

## Overview

The **CorpConnect Employee Portal** is a web-based platform designed to streamline communication, task management, and administrative operations between employees and management. It features separate login portals for **Admin** and **Employees**, ensuring secure access and role-based functionalities.

---

## Project Goals

- Provide a **user-friendly interface** for employees to manage their work-related tasks.
- Allow administrators to efficiently **manage employee data** and monitor operations.
- Ensure **secure authentication** and **role-based access control**.
- Offer a **responsive UI** using modern frontend technologies.

---

## Tech Stack

### Frontend

- **Framework**: Vue.js 3
- **Styling**: Tailwind CSS
- **State Management**: Pinia
- **Routing**: Vue Router
- **Build Tool**: Vite

### Backend

- **Framework**: PHP
- **Database**: MySQL
- **Password Security**: bcrypt.js for hashing
- **API Testing**: Curl
- **Deployment**: Render / Railway (or other Node hosting platform)

---

## Key Features

### Guest Page

- Redirect to either **Admin Login** or **Employee Login**.
- Modern, minimal design with Tailwind CSS.

### Admin Portal

- Dashboard with an overview of company activities.
- Manage employee profiles and roles.
- View and assign tasks.
- Generate reports.

### Employee Portal

- Secure login and profile management.
- View assigned tasks and deadlines.
- Submit task updates and reports.
- Internal communication with management.

---

## Development Plan

1. **Frontend**

   - Set up Vue.js project with Vite.
   - Install and configure Tailwind CSS.
   - Create Guest Page → redirects to Admin/Employee Login.
   - Build Login Pages for both roles.
   - Create Dashboard components (Admin/Employee).
   - Implement state management with Pinia.

2. **Backend**

   - Initialize PHP.
   - Set up MySQL connection.
   - Create models for:
     - **User** (role-based: admin/employee)
     - **Task**
   - Implement authentication routes (login, register).
   - Integrate backend APIs with frontend.

3. **Integration**

   - Connect frontend login forms to backend authentication APIs.
   - Implement role-based redirects after login.
   - Ensure proper error handling and validation.

4. **Testing & Deployment**
   - Test backend APIs with curl.
   - Test frontend workflows in browsers.
   - Deploy backend to Render/Railway.
   - Deploy frontend to Netlify/Vercel.

---

## Future Enhancements

- Implement file upload for employee documents.
- Add chat/messaging feature between employees and admin.
- Include analytics dashboard for HR insights.
- Add notification system (email or in-app).

---

## Project Status

🚀 **Currently in development** — Guest page and login system implementation in progress.
