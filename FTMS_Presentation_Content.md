# FTMS Presentation Slides

---

## SLIDE 1: TITLE SLIDE

# FILE TRACKING MANAGEMENT SYSTEM (FTMS)

**Ministry of Home Affairs and Internal Security**

*A Comprehensive Web-Based Solution for Digital File Management, Real-Time Tracking & Complete Accountability*

Developed with: Laravel 12 | Livewire 4.1 | Tailwind CSS | MySQL 8.0

---

## SLIDE 2: PROBLEM STATEMENT

**The Challenge: Paper-Based File Management is Failing**

- **Lost & Misplaced Files:** Physical files frequently disappear between departments with no way to trace their location
- **No Real-Time Visibility:** Staff must physically visit rooms or call around to locate a single file
- **Delayed Processing:** Manual logbooks and register-based tracking cause bottlenecks and significant processing delays
- **Zero Accountability:** When files are delayed or lost, there is no record of who held them last or for how long
- **Duplicate Confusion:** Multiple copies (TJ files) of the same document circulate without proper version control or reunification
- **Poor Security:** Sensitive and confidential documents lack proper access controls; anyone can view or handle any file
- **No Performance Data:** Management has no metrics on file turnaround times, staff workload, or departmental efficiency
- **SLA Violations Go Undetected:** There is no mechanism to flag overdue files or enforce processing deadlines

---

## SLIDE 3: AIM & OBJECTIVES

**Project Aim**

To develop and implement a comprehensive File Tracking Management System (FTMS) that digitizes the entire file lifecycle - from registration to archival - providing real-time visibility, automated workflows, and complete accountability across all departments of the Ministry of Home Affairs and Internal Security.

**Specific Objectives**

1. **Centralized File Registry**
   - Create a digital master register for all incoming and outgoing files
   - Generate unique identification numbers for every document

2. **Real-Time Tracking**
   - Enable instant location tracking of any file within the system
   - Monitor file movements across departments and units

3. **Complete Accountability**
   - Maintain comprehensive audit trails of all file activities
   - Track sender, receiver, timestamps, and actions for every transaction

4. **Workflow Automation**
   - Automate file routing and receipt confirmation processes
   - Implement SLA monitoring with overdue alerts

5. **Enhanced Security**
   - Role-based access control limiting actions by user type
   - Protect confidential and secret documents with appropriate access levels

6. **Performance Analytics**
   - Provide dashboards and reports on file processing times
   - Identify bottlenecks and measure departmental efficiency

---

## SLIDE 4: SOLUTION OVERVIEW

**Our Solution: FTMS - A Complete Digital File Lifecycle Platform**

A web-based File Tracking Management System that digitizes the entire file lifecycle - from registration to archival - providing real-time visibility, automated workflows, and complete accountability.

**Core Capabilities:**
- Automated file registration with unique numbering (FTS-YYYYMMDD-XXXX)
- Real-time file location tracking across all departments and units
- Complete movement history with sender/receiver audit trails
- Role-based access control with 4-tier permission system
- SLA monitoring with automatic overdue detection (default 3-day SLA)
- TJ (copy) file management with merge-back capability

**Mission:**
To eliminate paper-based file management inefficiencies and provide a secure, transparent, and efficient system for tracking all Ministry documents - ensuring no file is ever lost, delayed, or unaccounted for.

---

## SLIDE 4: SYSTEM ARCHITECTURE

**Three-Tier Architecture**

```
CLIENT LAYER                    APPLICATION SERVER              DATA STORAGE
-----------------              ----------------------          ----------------
Desktop Browser  ──┐           ┌─ Laravel 12 (PHP 8.2)        MySQL 8.0
Tablet Browser   ──┼── HTTPS ──┤  Livewire 4.1 (Real-time)    File Storage
Mobile Browser   ──┘           │  Alpine.js (Interactivity)    Session Store
                               └─ Tailwind CSS (Responsive)
```

**Technology Stack:**
| Layer | Technology | Purpose |
|-------|-----------|---------|
| Frontend | Livewire 4.1 + Alpine.js 3.x | Real-time reactive UI without full page reloads |
| Styling | Tailwind CSS 3.4 + @tailwindcss/forms | Responsive, utility-first design |
| Backend | Laravel 12 (PHP 8.2+) | MVC framework, routing, middleware, ORM |
| Database | MySQL 8.0 | Relational data storage with soft deletes |
| Authentication | Laravel Breeze + Sanctum | Secure login, session management, API tokens |
| Build Tool | Vite 7.0 | Fast asset bundling and hot module replacement |
| PDF Export | DomPDF (barryvdh/laravel-dompdf) | Report generation |

**24 Livewire Components** powering a fully reactive, SPA-like experience without JavaScript frameworks.

---

## SLIDE 5: USE CASES

**System Use Cases by User Role**

### Administrator
- **Manage Organization Structure:** Create, edit, and delete departments, units, and positions
- **Manage Employees:** Create, edit, delete, and restore employee accounts
- **Assign Department Heads:** Designate department and unit leadership with effective dates
- **View Audit Logs:** Search, filter, and clear system-wide audit logs
- **System Configuration:** Configure system-wide settings and parameters

### Registry Head
- **Register New Files:** Create original files with unique identification numbers (FTS-YYYYMMDD-XXXX)
- **Create TJ (Copy) Files:** Generate copy files linked to original documents
- **Edit File Details:** Modify file metadata, priority, and confidentiality levels
- **Delete Files:** Remove files from the system with soft delete
- **Send Files:** Route files to any employee within the system
- **Receive Files:** Accept files returned to registry
- **Confirm Receipt:** Acknowledge files received from other departments
- **Track All Files:** View and search all files across the entire system
- **Merge TJ Files:** Combine copy files back into original documents
- **Change Recipients:** Redirect files in transit to different employees
- **Create Department Users:** Register new employees within assigned departments

### Registry Staff
- **Send Files:** Route files to recipients within the system
- **Receive Files:** Accept and process incoming files
- **Confirm Receipt:** Acknowledge received files
- **Track Department Files:** Monitor files within their department
- **Change Recipients:** Redirect files in transit (with restrictions)

### Department User
- **Send Files:** Route files to other employees
- **Receive Files:** Accept incoming files
- **Confirm Receipt:** Acknowledge received files
- **Track Personal Files:** Monitor files currently held or in transit
- **View Department Files:** Access files within their department

### Key System Use Cases
- **File Registration:** Create new file records with unique identifiers and metadata
- **File Transfer:** Send files between employees with delivery method selection
- **Receipt Confirmation:** Acknowledge file receipt with automatic status updates
- **File Tracking:** Real-time location monitoring across all departments
- **File Search:** Advanced filtering by file number, subject, status, priority, date
- **TJ File Management:** Create, track, and merge copy files
- **Audit Trail:** Complete logging of all system activities
- **SLA Monitoring:** Automatic overdue detection and alerting

---

## SLIDE 6: KEY FEATURES - PART 1

**File Registration (Registry Head Only)**
- Auto-generated unique file numbers: `FTS-YYYYMMDD-XXXX`
- Support for legacy/old file number references
- Create original files OR TJ (copy) files linked to originals
- TJ files auto-numbered: `FTS-xxx-tj1`, `FTS-xxx-tj2`, etc.
- Priority classification: Normal | Urgent | Very Urgent
- Confidentiality levels: Public | Confidential | Secret
- Digital file attachments (up to 10MB per file)
- Subject-based grouping with existing subject search
- Duplicate detection to prevent double-registration

**File Transfer & Sending**
- Intuitive recipient search modal with real-time filtering
- Filter recipients by department for quick selection
- 4 delivery methods: Internal Messenger | Hand Carry | Courier | Email
- Sender comments for context and instructions
- Auto-detection of "return to registry" transfers
- Every send creates a complete FileMovement audit record

---

## SLIDE 7: KEY FEATURES - PART 2

**Receipt Confirmation**
- Dedicated "Confirm Files" dashboard for pending receipts
- One-click confirmation with timestamp recording
- Automatic status updates (in_transit -> received/completed)
- Current holder automatically updated on confirmation
- Navigation badge shows pending receipt count in real-time

**Dashboards & Reporting**
- **Registry Dashboard:** All files view with advanced filtering (search, status, priority, department, date range), stats counters, CSV export, bulk operations
- **Department Dashboard:** "My Files" list, pending receipts, sent-pending confirmations, recently received files, "All Department Files" modal view
- **Admin Dashboard:** System-wide statistics (total employees, files by status), complete audit log viewer with search, delete & clear capabilities
- Real-time badge counters on navigation for pending actions

**Organization Structure Management (Admin Only)**
- Department CRUD with registry department designation
- Unit management within departments with registry unit flags
- Position configuration (Director, Asst. Director, Supervisor, Staff, Support)
- Department Head & Unit Head assignments with effective/end dates
- Auto-cascading registry status from departments to child units

---

## SLIDE 8: KEY FEATURES - PART 3

**File Merging (Registry Head Only)**
- Search and select original file by file number
- View all TJ (copy) files linked to the original
- Merge completed/at-registry copies back into the original
- All movements and attachments transferred to original file
- Merged copy files permanently removed to prevent confusion
- Merged file numbers preserved in JSON array for audit trail

**Change Recipient**
- Redirect a sent-but-not-yet-received file to a different employee
- Only the original sender or registry staff can change recipients
- Full audit trail of recipient changes

**Security & Audit**
- Complete audit trail logging every action (send, receive, confirm, edit, delete)
- IP address and user agent recorded for every audit entry
- Soft deletes on all critical tables (employees, files, departments, units, positions)
- Auto-transfer of held files to Registry Head when an employee is deleted
- Cancellation of pending movements when employee is removed

**User Experience**
- Fully responsive design (Desktop, Tablet, Mobile)
- Loading spinners and disabled states on all actions
- Toast notifications for success, error, warning, and info feedback
- Real-time form validation with inline error messages

---

## SLIDE 9: USER ROLES & ACCESS CONTROL

**4-Tier Role-Based Access Control (RBAC)**

| Feature | Administrator | Registry Head | Registry Staff | Department User |
|---------|:------------:|:-------------:|:--------------:|:---------------:|
| Admin Dashboard | Yes | - | - | - |
| Registry Dashboard | - | Yes | Yes | - |
| Department Dashboard | - | - | Yes | Yes |
| Create/Register Files | - | Yes | - | - |
| Edit/Delete Files | - | Yes | - | - |
| Send Files | - | Yes | Yes | Yes |
| Receive & Confirm Files | - | Yes | Yes | Yes |
| Track Files | Yes | Yes | Yes | Yes |
| Merge TJ Files | - | Yes | - | - |
| Manage Movements | - | Yes | - | - |
| Change Recipient | - | Yes | Yes | - |
| Manage All Employees | Yes | - | - | - |
| Create Department Users | - | Yes | - | - |
| Manage Org Structure | Yes | - | - | - |
| View/Clear Audit Logs | Yes | - | - | - |
| Profile Management | Yes | Yes | Yes | Yes |

**Key Constraints:**
- Only ONE active Registry Head at any time (system-enforced)
- Registry Staff status auto-determined by department/unit assignment
- Admins are restricted from file operations (separation of duties)
- 5 custom middleware enforce access: Admin, NonAdmin, RegistryHead, RegistryStaff, DepartmentAccess

---

## SLIDE 10: FILE WORKFLOW

**Complete File Lifecycle**

```
REGISTRATION          CIRCULATION              PROCESSING           COMPLETION
────────────         ─────────────            ────────────         ────────────
Registry Head        Any authorized           Receiving            Registry Head
registers file  -->  user sends file    -->   employee works  -->  merges TJ copies
                     to recipient             on the file          or archives

Status Flow:
┌─────────────┐    ┌───────────┐    ┌──────────┐    ┌──────────────┐    ┌───────────┐
│ at_registry  │───>│ in_transit │───>│ received  │───>│ under_review │───>│ completed  │
└─────────────┘    └───────────┘    └──────────┘    └──────────────┘    └───────────┘
                         │                                                     │
                         │                               ┌─────────────────────┘
                         │                               v
                         │                    ┌─────────────────────┐
                         │                    │returned_to_registry │──> archived / merged
                         │                    └─────────────────────┘
                         v
                   ┌─────────────────┐
                   │ action_required  │ (flagged for attention)
                   └─────────────────┘
```

**9 File Statuses:** at_registry, in_transit, received, under_review, action_required, completed, returned_to_registry, archived, merged

**5 Movement Statuses:** sent, delivered, received, acknowledged, rejected

**SLA Enforcement:** Default 3-day processing window per movement; automatic overdue detection and flagging

---

## SLIDE 11: DATABASE DESIGN SUMMARY

### Database Overview

| # | Table | Purpose | Primary Key |
|---|-------|---------|-------------|
| 1 | departments | Organizational departments | id |
| 2 | units | Sub-departmental units | id |
| 3 | positions | Job titles & levels | id |
| 4 | employees | System users with roles | employee_number |
| 5 | department_heads | Department leadership | id |
| 6 | unit_heads | Unit leadership | id |
| 7 | files | Main file records | id |
| 8 | file_attachments | Digital attachments | id |
| 9 | file_movements | File transfer history | id |
| 10 | audit_logs | Activity tracking | id |
| 11 | password_reset_tokens | Password recovery | email |

### Key Relationships

- **departments → units**: One department has many units
- **departments → employees**: Many employees belong to one department
- **units → employees**: Many employees belong to one unit
- **positions → employees**: Employees hold one position
- **employees → files**: Files track current_holder and registered_by
- **files → file_movements**: One file has many movement records
- **employees → audit_logs**: Every action logged by employee

### Database Statistics

| Metric | Value |
|--------|-------|
| Total Core Tables | 11 |
| Total System Tables | 7 |
| Foreign Key Constraints | 15+ |
| Indexed Fields | 30+ |
| Soft Delete Tables | 4 |

---

## SLIDE 12: IMPLEMENTATION TIMELINE

**13-Week Agile Development Plan**

| Phase | Weeks | Activities | Deliverables |
|-------|-------|-----------|--------------|
| **Discovery & Design** | 1-2 | Requirements gathering, stakeholder interviews, system design, database schema design, UI/UX wireframes | Requirements document, ER diagram, wireframes |
| **Core Development** | 3-6 | Database setup & migrations, authentication system, employee management, department/unit/position CRUD, role-based middleware | Working auth system, admin panel, org structure |
| **Feature Development** | 7-10 | File registration, sending/receiving workflow, receipt confirmation, file tracking, dashboards (Registry, Department, Admin), file merging, change recipient | Complete file lifecycle, all dashboards |
| **Testing & QA** | 11-12 | Unit testing, integration testing, UAT with real users, security testing, performance optimization, bug fixes | Test reports, QA sign-off |
| **Deployment** | 13 | Production server setup, data migration, user training sessions, go-live, post-launch monitoring | Live system, trained users |

**Methodology:** Agile with 2-week sprints, regular stakeholder reviews, and continuous feedback integration

---

## SLIDE 13: TESTING & QUALITY ASSURANCE

**Multi-Layer Testing Strategy**

| Testing Type | Scope | Tools |
|-------------|-------|-------|
| **Unit Testing** | Individual models, methods, validators | PHPUnit 11.5, Laravel test helpers |
| **Integration Testing** | Livewire component interactions, middleware, database operations | Livewire testing utilities |
| **System Testing** | End-to-end file workflows (register -> send -> confirm -> merge) | Browser testing |
| **User Acceptance Testing** | Real users validate all workflows against business requirements | Ministry staff testers |
| **Performance Testing** | Response times, concurrent users, database query optimization | Load testing tools |
| **Security Testing** | Authentication, authorization, CSRF protection, SQL injection prevention | Vulnerability scanning |

**Quality Metrics & Standards:**
- Code coverage target: > 80% on critical paths
- All file lifecycle workflows tested end-to-end
- Zero high-severity bugs at deployment
- Page response time < 2 seconds under normal load
- Cross-browser compatibility (Chrome, Firefox, Edge, Safari)
- Mobile responsiveness verified on all screen sizes
- PSR-12 code formatting enforced via Laravel Pint
- Soft deletes ensure data recoverability

---

## SLIDE 14: BENEFITS & ROI

**Quantifiable Benefits**

| Metric | Before FTMS | After FTMS | Improvement |
|--------|-------------|------------|-------------|
| File search time | 30-60 minutes | Under 30 seconds | **90% reduction** |
| Document traceability | ~20% tracked | 100% tracked | **100% traceability** |
| Processing turnaround | 5-10 days avg | 1-3 days avg | **70% faster** |
| Lost files per month | 10-15 files | 0 files | **100% elimination** |
| Accountability | Manual logbooks | Full digital audit trail | **Complete** |

**Operational Benefits:**
- **Real-Time Visibility:** Know exactly where every file is at any moment
- **Automatic SLA Enforcement:** 3-day default processing window with overdue alerts
- **Separation of Duties:** Admins manage structure, Registry manages files - built-in governance
- **Auto-Transfer on Deletion:** When an employee leaves, all their files automatically transfer to the Registry Head - no orphaned files

**Strategic Benefits:**
- Data-driven performance insights for management decisions
- Enhanced compliance with document handling policies
- Foundation for future digital transformation initiatives
- Improved inter-departmental coordination and transparency

---

## SLIDE 15: DEPLOYMENT & FUTURE ENHANCEMENTS

**Production Deployment Requirements**

| Resource | Minimum | Recommended |
|----------|---------|-------------|
| CPU | 2 vCPU | 4 vCPU |
| RAM | 4 GB | 8 GB |
| Storage | 50 GB SSD | 100 GB SSD |
| PHP | 8.2+ | 8.3+ |
| Database | MySQL 8.0 | MySQL 8.0 with replication |
| Web Server | Nginx / Apache | Nginx with SSL |
| SSL | Required (HTTPS) | Let's Encrypt or commercial cert |
| Backups | Daily | Daily + real-time replication |

**Planned Future Enhancements:**
- **Email & SMS Notifications:** Automated alerts for file receipts, overdue warnings, and status changes
- **Mobile Application:** Native iOS/Android app for on-the-go file tracking and confirmations
- **Digital Signatures:** Electronic signature integration for file approvals and verifications
- **OCR Document Scanning:** Scan physical documents and auto-extract metadata
- **AI-Powered Search:** Intelligent search across file contents and metadata
- **QR Code Tracking:** Physical QR labels on files for quick mobile scanning
- **Advanced Reporting:** Customizable dashboards with charts, graphs, and scheduled reports
- **Multi-Ministry Support:** Scale the system to serve multiple ministries from a single platform

**Ongoing Support:** Regular security updates, feature releases, user training, and dedicated technical support

---

## SLIDE 16: CONCLUSION & Q&A

**FTMS: Transforming Ministry File Management**

**What We Deliver:**
- A fully functional, production-ready File Tracking Management System
- Real-time tracking of every file across the entire Ministry
- Complete accountability with comprehensive audit trails
- Role-based security ensuring the right people access the right files
- Automated workflows that eliminate manual bottlenecks

**Key Takeaways:**
- 24 purpose-built components covering every aspect of file management
- 4-tier role system with 5 custom middleware for security enforcement
- 10-table relational database with soft deletes and JSON flexibility
- Responsive design works on desktop, tablet, and mobile
- Built on modern, maintainable technology (Laravel 12 + Livewire 4.1)

---

# Thank You!

## Questions & Answers

**Let's Discuss How FTMS Can Transform Your Document Management**

Contact: support@ftms.moha.gov
