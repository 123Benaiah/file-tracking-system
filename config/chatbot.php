<?php

return [

    /*
    |--------------------------------------------------------------------------
    | FTMS Chatbot Knowledge Base
    |--------------------------------------------------------------------------
    |
    | This configuration file contains the knowledge base for the FTMS AI
    | chatbot, including system information, file workflow, roles, and
    | common Q&A for keyword-based fallback responses.
    |
    */

    'system' => [
        'name' => 'File Tracking Management System (FTMS)',
        'organization' => 'Ministry of Home Affairs (MOHA)',
        'description' => 'A digital system for tracking the movement of physical files across departments, units, and the central registry within the Ministry of Home Affairs.',
    ],

    'file_statuses' => [
        'at_registry' => 'File is currently held at the central registry, awaiting dispatch or action.',
        'in_transit' => 'File has been sent and is awaiting receipt by the intended recipient.',
        'received' => 'File has been received by the recipient but not yet confirmed/acknowledged.',
        'under_review' => 'File is being reviewed or worked on by the current holder.',
        'action_required' => 'File requires immediate action from the current holder.',
        'completed' => 'All actions on the file have been completed.',
        'finished' => 'File processing is fully finished.',
        'returned_to_registry' => 'File has been returned to the central registry after department processing.',
        'archived' => 'File has been archived and is no longer active.',
        'merged' => 'File has been merged into another file.',
    ],

    'movement_statuses' => [
        'sent' => 'File has been dispatched by the sender but not yet delivered.',
        'delivered' => 'File has been physically delivered to the recipient location.',
        'received' => 'Recipient has acknowledged physical receipt of the file.',
        'acknowledged' => 'Recipient has formally acknowledged and accepted the file.',
        'rejected' => 'Recipient has rejected the file (e.g., wrong recipient, damaged).',
    ],

    'priority_levels' => [
        'normal' => 'Standard processing priority.',
        'urgent' => 'Requires expedited handling and attention.',
        'very_urgent' => 'Highest priority — requires immediate action.',
    ],

    'confidentiality_levels' => [
        'public' => 'Accessible to all authenticated users in the system.',
        'confidential' => 'Restricted access — only visible to relevant department users.',
        'secret' => 'Highly restricted — limited to specific authorized personnel.',
    ],

    'roles' => [
        'admin' => 'System Administrator — manages employees, departments, units, positions, and system configuration. Cannot access file operations.',
        'registry_head' => 'Registry Head — manages the central registry, registers new files, assigns file numbers, manages users, sends/receives files, and oversees all file movements.',
        'registry_staff' => 'Registry Staff — assists with file operations at the registry level, can send/receive/confirm files.',
        'department_user' => 'Department User — receives files sent to their department, can review, take action, and return files to the registry.',
    ],

    'workflow' => [
        'description' => 'The file tracking workflow follows this lifecycle:',
        'steps' => [
            '1. Registration — Registry Head registers a new file with a file number, subject, and metadata.',
            '2. Send — Registry staff or head sends the file to a specific employee in a department.',
            '3. In Transit — File is marked as in-transit while being physically moved.',
            '4. Receive — Recipient receives the physical file and marks it as received in the system.',
            '5. Confirm — Recipient confirms/acknowledges receipt of the file.',
            '6. Action — Recipient reviews the file and takes necessary action.',
            '7. Complete/Return — File is either completed or returned to the registry for further routing.',
        ],
    ],

    'features' => [
        'File Registration' => 'Register new files with unique file numbers (old and new format).',
        'File Tracking' => 'Track any file by file number to see its current location and movement history.',
        'Send Files' => 'Send files to specific employees with notes and priority levels.',
        'Receive Files' => 'View and receive files that have been sent to you.',
        'Confirm Files' => 'Confirm/acknowledge receipt of received files.',
        'File Movements' => 'View complete movement history of any file.',
        'TJ (Temporary Jacket) Files' => 'Create temporary jacket copies of original files for parallel processing.',
        'File Merging' => 'Merge multiple related files into a single file.',
        'Overdue Tracking' => 'Monitor files that have passed their due dates.',
        'Dashboard' => 'Role-specific dashboards showing relevant file statistics and actions.',
        'Change Recipient' => 'Change the recipient of a file that is still in transit.',
        'Sent Pending' => 'View files you have sent that are still pending receipt.',
    ],

    'keyword_responses' => [
        'track file' => 'To track a file, go to **File Tracking** from the navigation menu. Enter the file number (old or new format) to see its current location, holder, and complete movement history.',
        'send file' => 'To send a file: Navigate to the file details, click **Send**, select the recipient employee, add optional notes, set the priority, and confirm. The file will be marked as "In Transit".',
        'receive file' => 'To receive files: Go to **Receive Files** from the menu. You will see all files sent to you that are pending receipt. Click **Receive** to confirm you have the physical file.',
        'confirm file' => 'To confirm a file: Go to **Confirm Files** from the menu. Select the file you received and click **Confirm** to formally acknowledge receipt.',
        'status' => 'File statuses include: **At Registry** (held at registry), **In Transit** (being moved), **Received** (physically received), **Under Review** (being worked on), **Action Required** (needs attention), **Completed** (done), **Returned to Registry** (sent back).',
        'help' => 'I can help you with: tracking files, understanding file statuses, sending/receiving files, navigating the dashboard, and general FTMS questions. Just ask!',
        'register file' => 'To register a new file: Only the **Registry Head** can register files. Go to **Create File**, enter the file subject, file numbers (old/new format), set priority and confidentiality, and submit.',
        'movement' => 'File movements track every time a file changes hands. Each movement records: sender, recipient, timestamps, notes, and status. View movements from the file detail page.',
        'dashboard' => 'Your dashboard shows: files in your possession, pending actions, recent movements, and statistics. Registry users see all files; department users see files relevant to their department.',
        'profile' => 'Your profile page shows your employee details, department, position, and account settings. You can view your information from the **Profile** link in the navigation.',
        'overdue' => 'Overdue files are those that have passed their due date and are not yet completed or archived. Check your dashboard for overdue alerts. Urgent attention is needed for these files.',
        'urgent' => 'Urgent and Very Urgent files require expedited handling. They are highlighted on dashboards and in file lists. Prioritize these files for immediate action.',
        'merge' => 'File merging combines multiple related files into one. Only the **Registry Head** can merge files. Go to **Merge Files**, select the target file, choose files to merge, and confirm.',
        'tj' => 'TJ (Temporary Jacket) files are temporary copies of an original file, allowing the same file to be processed in multiple departments simultaneously. They are linked to the original file number.',
        'change recipient' => 'If a file was sent to the wrong person, you can change the recipient while it is still "In Transit". Go to **Sent Pending** files and click **Change Recipient**.',
    ],

    'openai' => [
        'model' => env('CHATBOT_OPENAI_MODEL', 'gpt-4o-mini'),
        'api_key' => env('OPENAI_API_KEY', ''),
        'max_tokens' => 500,
        'temperature' => 0.7,
    ],

];
