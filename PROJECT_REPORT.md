# File Tracking Management System (FTMS)
## Project Report

---

**Project Name:** File Tracking Management System (FTMS)  
**Organization:** Ministry of Home Affairs and Internal Security  
**Version:** 1.0  
**Date:** March 2026  

---

## Table of Contents

1. [Executive Summary](#1-executive-summary)
2. [Project Background](#2-project-background)
3. [Problem Statement](#3-problem-statement)
4. [Objectives](#4-objectives)
5. [Scope of Work](#5-scope-of-work)
6. [Technology Stack](#6-technology-stack)
7. [System Architecture](#7-system-architecture)
8. [Database Design](#8-database-design)
9. [Features and Functionality](#9-features-and-functionality)
10. [User Roles and Permissions](#10-user-roles-and-permissions)
11. [Implementation Details](#11-implementation-details)
12. [Testing Strategy](#12-testing-strategy)
13. [Deployment Plan](#13-deployment-plan)
14. [Recommendations](#14-recommendations)

---

## 1. Executive Summary

The **File Tracking Management System (FTMS)** is a comprehensive *web-based application* designed to **digitize and streamline** the management of physical files within the Ministry of Home Affairs. 

### Key Highlights

- **Real-time file tracking** across all departments
- **Complete audit trail** for accountability
- **Role-based access control** for secure authentication
- **Dashboard analytics** for decision-making
- **CSV export** functionality for reporting

> *"FTMS represents a significant step in the Ministry's digital transformation journey."*

---

## 2. Project Background

### 2.1 Organizational Context

The Ministry of Home Affairs manages **thousands of physical files** daily across multiple departments and units. These files contain:

- Critical government documents
- Administrative records
- Correspondence files
- Confidential reports

### 2.2 Current Challenges

The traditional paper-based system has led to:

1. Files getting lost or misplaced
2. No real-time visibility of file locations
3. Manual documentation overhead
4. Limited accountability
5. Compliance and audit challenges

---

## 3. Problem Statement

### Key Problems Identified

| Problem | Impact | Solution |
|---------|--------|----------|
| Lost files | Delayed processing | Real-time tracking |
| Manual logs | Time-consuming | Automated records |
| No accountability | Poor responsibility | Complete audit trail |
| Limited visibility | No metrics | Dashboard analytics |

### 3.1 Specific Pain Points

- **Lack of Real-Time Tracking:** No reliable way to determine current file location
- **Poor Accountability:** No systematic method to track sender/receiver
- **Manual Documentation:** Staff spent time maintaining physical registers
- **Limited Visibility:** No centralized view of file processing status
- **Compliance Challenges:** No comprehensive audit trail

---

## 4. Objectives

### 4.1 Primary Objectives

1. **Implement Real-Time File Tracking**
   - Provide instant visibility into file locations
   - Track files from registration to completion

2. **Establish Comprehensive Audit Trails**
   - Record sender, receiver, timestamps, and comments
   - Ensure full accountability

3. **Automate File Movement Documentation**
   - Replace manual logbooks with digital records
   - Capture all transfer activities automatically

4. **Implement Role-Based Access Control**
   - Secure authentication system
   - Distinct roles for different user types

5. **Enable Instant Notifications**
   - Alert recipients when files are sent
   - Eliminate communication delays

6. **Provide Performance Analytics**
   - Display key metrics
   - Show pending items and overdue documents

### 4.2 Secondary Objectives

- Improve processing efficiency by **60%**
- Enhance security with confidentiality classifications
- Support compliance with government standards
- Enable mobile accessibility

---

## 5. Scope of Work

### 5.1 In-Scope Items

- [ ] Web-based file tracking interface
- [ ] User authentication and authorization
- [ ] File lifecycle management (register, send, receive, track)
- [ ] Organizational structure management
- [ ] Dashboard and reporting
- [ ] Audit logging
- [ ] Notification system

### 5.2 Out-of-Scope Items

- Document scanning and OCR
- Digital signature integration
- Mobile native applications
- External system integrations
- Automated workflow routing
- Physical barcode/QR code generation

---

## 6. Technology Stack

### 6.1 Technology Matrix

| Component | Technology | Version | Purpose |
|-----------|------------|---------|---------|
| **Backend** | Laravel | 12.0 | Application framework |
| **Frontend** | Livewire | 3.0+ | Interactive components |
| **Language** | PHP | 8.2+ | Server-side scripting |
| **Styling** | Tailwind CSS | 3.x | UI styling |
| **Database** | MySQL | 8.0 | Data storage |
| **Auth** | Laravel Breeze | 2.3 | Authentication |
| **PDF** | DOMPDF | 3.1 | PDF generation |
| **Build** | Vite | Latest | Asset bundling |
| **Testing** | PHPUnit | 11.0+ | Unit testing |

### 6.2 System Requirements

#### Development Environment

```
Minimum:
- CPU: Intel Core i3 / AMD Ryzen 3
- RAM: 8 GB
- Storage: 256 GB SSD
- Display: 1366x768

Recommended:
- CPU: Intel Core i5 / AMD Ryzen 5
- RAM: 16 GB
- Storage: 512 GB SSD
- Display: 1920x1080
```

#### Production Server

```
Web Server:
- CPU: 2-4 vCPU cores
- RAM: 4-8 GB
- Storage: 50-100 GB SSD
- Network: 100 Mbps - 1 Gbps

Database Server:
- CPU: 2-4 vCPU cores
- RAM: 4-8 GB
- Storage: 100-500 GB SSD
```

---

## 7. System Architecture

### 7.1 High-Level Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                      CLIENT LAYER                           │
│  ┌───────────┐  ┌───────────┐  ┌───────────┐              │
│  │  Desktop  │  │  Tablet   │  │  Mobile   │              │
│  │  Browser  │  │  Browser  │  │  Browser  │              │
│  └───────────┘  └───────────┘  └───────────┘              │
└─────────────────────────────────────────────────────────────┘
                            │
                            ▼ HTTPS
┌─────────────────────────────────────────────────────────────┐
│                 APPLICATION SERVER LAYER                    │
│                  Laravel 12.0 + Livewire                    │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐  │
│  │  Router  │  │Middleware│  │Controller│  │ Livewire │  │
│  └──────────┘  └──────────┘  └──────────┘  └──────────┘  │
└─────────────────────────────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────────┐
│                    DATA STORAGE LAYER                       │
│  ┌─────────────────────────┐  ┌─────────────────────────┐ │
│  │       MySQL 8.0         │  │     File Storage        │ │
│  │  - Employees            │  │  - Attachments          │ │
│  │  - Files                │  │  - Exports              │ │
│  │  - File Movements       │  │                         │ │
│  │  - Audit Logs           │  │                         │ │
│  └─────────────────────────┘  └─────────────────────────┘ │
└─────────────────────────────────────────────────────────────┘
```

### 7.2 Directory Structure

```
app/
├── Console/Commands/          # Artisan commands
├── Http/
│   ├── Controllers/Auth/    # Authentication controllers
│   ├── Middleware/         # Custom middleware
│   └── Requests/           # Form validation
├── Livewire/               # Livewire components
│   ├── Admin/              # Admin panel
│   ├── Dashboard/          # Dashboard components
│   ├── Files/              # File management
│   ├── Layout/             # Navigation
│   └── Profile/            # User profile
├── Models/                 # Eloquent models
├── Traits/                 # Reusable traits
└── View/Components/        # Blade components

database/
├── migrations/            # Database migrations
└── seeders/               # Database seeders

resources/views/
├── components/            # UI components
└── livewire/             # Livewire templates

routes/
└── web.php              # Application routes
```

### 7.3 Authentication Flow

```php
// Authentication Process
1. User submits employee_number and password
2. Laravel Auth validates credentials against database
3. Session created with user data upon success
4. Auth middleware verifies valid session
5. Role middleware checks specific requirements
6. Access granted or denied based on checks
```

---

## 8. Database Design

### 8.1 Entity Relationship Diagram

```
┌──────────────┐       ┌──────────────┐       ┌──────────────┐
│  Department  │ 1   * │    Unit      │ *   1│  Employee    │
├──────────────┤───────├──────────────┤───────├──────────────┤
│ id           │       │ id           │       │ emp_number   │
│ name         │       │ department_id│──────▶│ department_id│
│ code         │       │ name         │       │ unit_id      │
│ location     │       │ code         │       │ position_id  │
│ has_units    │       │ is_registry  │       │ role         │
└──────────────┘       └──────────────┘       └──────────────┘
                                                      │
       ┌──────────────────┬───────────────────────────┤
       │                  │                           │
       ▼                  ▼                           ▼
┌──────────────┐   ┌──────────────┐          ┌──────────────┐
│    File      │   │FileMovement  │          │   Position   │
├──────────────┤   ├──────────────┤          ├──────────────┤
│ id           │◄──│ file_id      │          │ id           │
│ file_number  │   │ sender_id    │          │ title        │
│ subject      │   │ receiver_id  │          │ code         │
│ status       │   │ sent_at      │          │ level        │
│ holder_id    │   │ status      │          │ position_type│
└──────────────┘   └──────────────┘          └──────────────┘
```

### 8.2 Database Tables

#### Employees Table

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| employee_number | VARCHAR(20) | PRIMARY KEY | Unique employee ID |
| name | VARCHAR(255) | NOT NULL | Full name |
| email | VARCHAR(255) | UNIQUE, NOT NULL | Email address |
| password | VARCHAR(255) | NOT NULL | Hashed password |
| role | ENUM | DEFAULT 'user' | admin or user |
| is_admin | BOOLEAN | DEFAULT FALSE | Admin flag |
| is_registry_head | BOOLEAN | DEFAULT FALSE | Registry Head flag |
| is_registry_staff | BOOLEAN | DEFAULT FALSE | Registry Staff flag |
| department_id | BIGINT | FOREIGN KEY | Department reference |
| unit_id | BIGINT | FOREIGN KEY | Unit reference |
| position_id | BIGINT | FOREIGN KEY | Position reference |
| is_active | BOOLEAN | DEFAULT TRUE | Active status |

#### Files Table

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT | PRIMARY KEY | Auto-increment ID |
| file_number | VARCHAR(50) | UNIQUE | FTS-YYYYMMDD-XXXX |
| file_name | VARCHAR(255) | NOT NULL | File name |
| subject | VARCHAR(255) | NOT NULL | File subject |
| title | VARCHAR(500) | NULLABLE | Full title |
| priority | ENUM | DEFAULT 'normal' | normal/urgent/very_urgent |
| confidentiality | ENUM | DEFAULT 'public' | public/confidential/secret |
| status | ENUM | DEFAULT 'at_registry' | File status |
| current_holder_employee_number | VARCHAR(20) | FOREIGN KEY | Current holder |
| registered_by_employee_number | VARCHAR(20) | FOREIGN KEY | Registrar |

#### File Movements Table

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT | PRIMARY KEY | Auto-increment ID |
| file_id | BIGINT | FOREIGN KEY | File reference |
| sender_employee_number | VARCHAR(20) | FOREIGN KEY | Sender |
| intended_receiver_employee_number | VARCHAR(20) | FOREIGN KEY | Intended receiver |
| actual_receiver_employee_number | VARCHAR(20) | FOREIGN KEY | Actual receiver |
| movement_status | ENUM | DEFAULT 'sent' | Movement status |
| sent_at | TIMESTAMP | DEFAULT NOW() | Sent timestamp |
| received_at | TIMESTAMP | NULLABLE | Received timestamp |
| delivery_method | ENUM | DEFAULT 'hand_carry' | Delivery method |

#### Departments Table

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT | PRIMARY KEY | Auto-increment ID |
| name | VARCHAR(255) | NOT NULL | Department name |
| code | VARCHAR(20) | UNIQUE | Department code |
| location | VARCHAR(255) | NULLABLE | Physical location |
| is_registry | BOOLEAN | DEFAULT FALSE | Is registry dept |
| has_units | BOOLEAN | DEFAULT TRUE | Has subunits |
| is_registry_department | BOOLEAN | DEFAULT FALSE | Registry designation |

#### Units Table

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT | PRIMARY KEY | Auto-increment ID |
| department_id | BIGINT | FOREIGN KEY | Parent department |
| name | VARCHAR(255) | NOT NULL | Unit name |
| code | VARCHAR(20) | UNIQUE | Unit code |
| is_registry | BOOLEAN | DEFAULT FALSE | Is registry unit |
| is_registry_unit | BOOLEAN | DEFAULT FALSE | Registry designation |

#### Positions Table

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT | PRIMARY KEY | Auto-increment ID |
| title | VARCHAR(255) | NOT NULL | Position title |
| code | VARCHAR(50) | UNIQUE | Position code |
| position_type | ENUM | DEFAULT 'staff' | Type classification |
| level | INT | DEFAULT 3 | Hierarchy level |
| employment_type | ENUM | DEFAULT 'permanent' | Employment type |

### 8.3 File Statuses

| Status | Description |
|--------|-------------|
| `at_registry` | Currently at registry |
| `in_transit` | Being transferred |
| `received` | Received by user |
| `under_review` | Being processed |
| `action_required` | Needs attention |
| `completed` | Processing complete |
| `returned_to_registry` | Back at registry |
| `archived` | Archived |
| `merged` | Merged into another file |

---

## 9. Features and Functionality

### 9.1 Core Features

#### Feature 1: File Registration & Management

- **Auto-generation** of unique file numbers (FTS-YYYYMMDD-XXXX format)
- Support for **legacy file number** references
- Priority classification: *Normal*, **Urgent**, *Very Urgent*
- Confidentiality levels: Public, **Confidential**, Secret
- **Digital attachment** support (up to 10MB)
- **SLA tracking** with default 3-day processing window

#### Feature 2: File Transfer & Tracking

- Intuitive file sending interface
- Recipient search with **department filtering**
- Multiple delivery methods:
  - Internal Messenger
  - Hand Carry
  - Courier
  - Email
- Optional sender comments and hand-carry designations
- **Complete movement history** tracking

#### Feature 3: Receipt Confirmation System

- Pending receipts dashboard
- **One-click confirmation**
- Automatic status updates
- Bulk confirmation capabilities
- Receiver comments support

#### Feature 4: Role-Based Access Control

Four distinct user roles implemented via:
- `role` field: 'admin' or 'user'
- `is_registry_head` boolean flag
- `is_registry_staff` boolean flag

#### Feature 5: Dashboard & Reporting

- **Registry Dashboard:** Statistics, pending receipts, recent activity
- **Department Dashboard:** My files, pending actions, department overview
- **Admin Dashboard:** System-wide metrics, audit logs
- **CSV export** functionality
- Real-time counters and visual indicators

#### Feature 6: Organizational Structure Management

- Department and unit hierarchy configuration
- Position management with **hierarchical levels**
- Department and unit head assignments
- Employee-to-department/unit mapping

#### Feature 7: File Merging

- Merge file copies back into original files
- Preservation of **complete movement history**
- Audit trail of merge operations

### 9.2 User Interfaces

#### Navigation Features

- Role-based menu items
- Notification badge counter
- User dropdown with avatar
- Mobile-responsive hamburger menu

#### Common UI Components

| Component | Description |
|-----------|-------------|
| Cards | Statistics, actions, hover effects |
| Buttons | Primary, secondary, danger variants |
| Forms | Real-time validation, helper text |
| Tables | Sortable, pagination, bulk actions |
| Modals | Overlay, header/body/footer structure |

#### Color Scheme

| Purpose | Color | Hex Code |
|---------|-------|----------|
| Primary | Indigo | `#4f46e5` |
| Secondary | Slate | `#64748b` |
| Success | Green | `#22c55e` |
| Warning | Amber | `#f59e0b` |
| Danger | Red | `#ef4444` |
| Background | Light Slate | `#f8fafc` |

---

## 10. User Roles and Permissions

### 10.1 Role Implementation

The system uses a **hybrid approach** combining:

```
role field: 'admin' or 'user'
    ↓
is_registry_head boolean (designates Registry Head)
    ↓
is_registry_staff boolean (designates Registry Staff)
```

### 10.2 Permission Matrix

| Feature | Admin | Registry Head | Registry Staff | Dept User |
|---------|:-----:|:-------------:|:--------------:|:---------:|
| **Dashboard** |
| Admin Dashboard | ✓ | ✗ | ✗ | ✗ |
| Registry Dashboard | ✗ | ✓ | ✓ | ✗ |
| Department Dashboard | ✗ | ✗ | ✗ | ✓ |
| **File Management** |
| Create Files | ✗ | ✓ | ✗ | ✗ |
| Edit Any File | ✗ | ✓ | ✗ | ✗ |
| Send Files | ✗ | ✓ | ✓ | ✓ |
| Receive/Confirm Files | ✗ | ✓ | ✓ | ✓ |
| Track Files | ✓ | ✓ | ✓ | ✓ |
| Merge Files | ✗ | ✓ | ✗ | ✗ |
| Manage Movements | ✗ | ✓ | ✗ | ✗ |
| **User Management** |
| Manage Employees | ✓ | ✗ | ✗ | ✗ |
| Create Department Users | ✗ | ✓ | ✗ | ✗ |
| **Organization** |
| Manage Departments | ✓ | ✗ | ✗ | ✗ |
| Manage Units | ✓ | ✗ | ✗ | ✗ |
| Manage Positions | ✓ | ✗ | ✗ | ✗ |
| Manage Heads | ✓ | ✗ | ✗ | ✗ |
| **System** |
| View Audit Logs | ✓ | ✗ | ✗ | ✗ |
| Export Data | ✓ | ✓ | ✓ | ✓ |

### 10.3 Middleware Implementation

| Middleware | Purpose |
|------------|---------|
| Admin | Checks if role = 'admin' |
| CheckRegistryHead | Allows is_registry_head = true |
| CheckRegistryStaff | Allows is_registry_staff = true |
| CheckDepartmentAccess | Allows department users |
| NonAdmin | Prevents admin access |

---

## 11. Implementation Details

### 11.1 Development Phases

| Phase | Duration | Activities |
|-------|----------|------------|
| Phase 1: Initiation | 2 weeks | Requirements, stakeholder identification |
| Phase 2: Design | 3 weeks | Architecture, database, UI/UX |
| Phase 3: Core Development | 8 weeks | Sprint cycles implementing features |
| Phase 4: Testing | 3 weeks | System testing, security audit, UAT |
| Phase 5: Deployment | 2 weeks | Production setup, data migration |
| Phase 6: Post-Launch | 4 weeks | Bug fixes, enhancements |

### 11.2 Key Classes and Components

#### Models

```php
// Employee Model
class Employee extends Authenticatable
{
    protected $primaryKey = 'employee_number';
    public $incrementing = false;
    
    public function departmentRel() { ... }
    public function unitRel() { ... }
    public function position() { ... }
    public function isRegistryHead() { ... }
}

// File Model
class File extends Model
{
    public function movements() { ... }
    public function attachments() { ... }
    public function currentHolder() { ... }
}

// FileMovement Model
class FileMovement extends Model
{
    public function file() { ... }
    public function sender() { ... }
    public function intendedReceiver() { ... }
}
```

#### Livewire Components

```
app/Livewire/
├── Admin/
│   ├── EmployeeManagement.php
│   ├── DepartmentManagement.php
│   └── UnitManagement.php
├── Dashboard/
│   ├── RegistryDashboard.php
│   ├── DepartmentDashboard.php
│   └── AdminDashboard.php
├── Files/
│   ├── FileRegistration.php
│   ├── SendFile.php
│   ├── ReceiveFile.php
│   └── TrackFile.php
└── Registry/
    └── UserManagement.php
```

### 11.3 Installation Steps

```bash
# 1. Clone repository
git clone https://github.com/123Benaiah/file-tracking-system.git
cd file-tracking-system

# 2. Install dependencies
composer install
npm install

# 3. Environment configuration
cp .env.example .env
php artisan key:generate

# 4. Database setup
php artisan migrate
php artisan db:seed

# 5. Storage setup
php artisan storage:link

# 6. Build assets
npm run dev

# 7. Start server
php artisan serve
```

### 11.4 Default Credentials

| Role | Employee Number | Password |
|------|----------------|----------|
| Registry Head | REGHEAD001 | Moha@2024 |
| Sample User | EMP001 | Password123 |

---

## 12. Testing Strategy

### 12.1 Testing Types

| Type | Description | Tools |
|------|-------------|-------|
| Unit Testing | Individual component testing | PHPUnit |
| Feature Testing | Feature-level testing | PHPUnit |
| Browser Testing | UI interaction testing | Laravel Dusk |
| Security Testing | Vulnerability assessment | Manual |

### 12.2 Test Coverage Areas

- [ ] User authentication (login/logout)
- [ ] File registration
- [ ] File sending and receiving
- [ ] Role-based access control
- [ ] Dashboard functionality
- [ ] CSV export
- [ ] File merging
- [ ] Audit logging

### 12.3 Running Tests

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --filter=Auth

# Run with coverage
php artisan test --coverage
```

---

## 13. Deployment Plan

### 13.1 Production Requirements

```
Minimum:
- CPU: 2 vCPU cores
- RAM: 4 GB
- Storage: 50 GB SSD
- PHP 8.2+
- MySQL 8.0

Recommended:
- CPU: 4 vCPU cores
- RAM: 8 GB
- Storage: 100 GB SSD
```

### 13.2 Deployment Steps

```bash
# Clone repository
git clone https://github.com/123Benaiah/file-tracking-system.git /var/www/ftms
cd /var/www/ftms

# Install dependencies
composer install --no-dev --optimize-autoloader
npm ci
npm run build

# Configure environment
cp .env.example .env
php artisan key:generate

# Run migrations
php artisan migrate --force

# Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# Create storage link
php artisan storage:link
```

### 13.3 Nginx Configuration

```nginx
server {
    listen 80;
    server_name ftms.yourdomain.com;
    root /var/www/ftms/public;
    
    index index.php index.html;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
    
    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

---

## 14. Recommendations

### 14.1 Immediate Actions

1. **Conduct User Training**
   - Train all staff on system usage
   - Create user manuals
   - Establish help desk support

2. **Data Migration**
   - Migrate existing file records
   - Validate data integrity
   - Back up legacy systems

3. **Pilot Testing**
   - Run pilot with one department
   - Gather feedback
   - Make necessary adjustments

### 14.2 Future Enhancements

| Enhancement | Priority | Description |
|-------------|----------|-------------|
| Mobile App | Medium | Native mobile application |
| Email Notifications | High | Automatic email alerts |
| SMS Integration | Medium | SMS notifications |
| API Development | High | RESTful API for integrations |
| Barcode Scanning | Medium | Physical barcode support |
| Workflow Automation | Low | Auto-routing of files |
| Multi-ministry Support | Low | Support for other ministries |

### 14.3 Maintenance Plan

- **Weekly:** Monitor system performance
- **Monthly:** Apply security updates
- **Quarterly:** Review and optimize database
- **Annually:** Full system audit

---

## Conclusion

The **File Tracking Management System (FTMS)** is a robust solution that addresses the Ministry's file management challenges. With its *real-time tracking*, **comprehensive audit trails**, and *role-based access control*, the system will significantly improve operational efficiency and accountability.

The successful implementation of FTMS will mark a significant milestone in the Ministry's digital transformation journey.

---

**Prepared by:** System Development Team  
**Date:** March 2026  
**Version:** 1.0

---

*This document is confidential and intended for internal use only.*
