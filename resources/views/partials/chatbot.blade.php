{{-- AI Chatbot Widget — Only shown for authenticated non-admin users --}}
@auth
@if(!auth()->user()->isAdmin())

<style>
    #chatbot-bubble {
        position: fixed;
        bottom: 24px;
        right: 24px;
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: linear-gradient(135deg, #16a34a, #15803d);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 4px 16px rgba(22, 163, 74, 0.4);
        z-index: 9999;
        transition: transform 0.2s, box-shadow 0.2s;
        border: none;
    }
    #chatbot-bubble:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 24px rgba(22, 163, 74, 0.5);
    }
    #chatbot-bubble svg {
        width: 28px;
        height: 28px;
    }

    #chatbot-window {
        position: fixed;
        bottom: 90px;
        right: 24px;
        width: 380px;
        max-height: 520px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
        z-index: 9999;
        display: none;
        flex-direction: column;
        overflow: hidden;
        border: 1px solid #e5e7eb;
    }
    #chatbot-window.open { display: flex; }

    #chatbot-header {
        background: linear-gradient(135deg, #16a34a, #15803d);
        color: white;
        padding: 14px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-shrink: 0;
    }
    #chatbot-header-title {
        font-weight: 600;
        font-size: 15px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    #chatbot-header-title svg {
        width: 20px;
        height: 20px;
    }
    #chatbot-close {
        background: none;
        border: none;
        color: white;
        cursor: pointer;
        padding: 4px;
        border-radius: 6px;
        display: flex;
        align-items: center;
    }
    #chatbot-close:hover { background: rgba(255,255,255,0.2); }

    #chatbot-messages {
        flex: 1;
        overflow-y: auto;
        padding: 16px;
        display: flex;
        flex-direction: column;
        gap: 12px;
        min-height: 280px;
        max-height: 340px;
        background: #f9fafb;
    }

    .chat-msg {
        max-width: 85%;
        padding: 10px 14px;
        border-radius: 12px;
        font-size: 13.5px;
        line-height: 1.5;
        word-wrap: break-word;
    }
    .chat-msg.bot {
        align-self: flex-start;
        background: white;
        color: #1f2937;
        border: 1px solid #e5e7eb;
        border-bottom-left-radius: 4px;
    }
    .chat-msg.user {
        align-self: flex-end;
        background: linear-gradient(135deg, #16a34a, #15803d);
        color: white;
        border-bottom-right-radius: 4px;
    }
    .chat-msg.bot .msg-source {
        display: block;
        font-size: 10px;
        color: #9ca3af;
        margin-top: 6px;
    }
    .chat-msg.bot strong { color: #15803d; }
    .chat-msg.bot code {
        background: #f3f4f6;
        padding: 1px 4px;
        border-radius: 3px;
        font-size: 12px;
    }
    .chat-msg.bot ul, .chat-msg.bot ol {
        margin: 4px 0;
        padding-left: 18px;
    }
    .chat-msg.bot li { margin-bottom: 2px; }

    .chat-typing {
        align-self: flex-start;
        display: flex;
        gap: 4px;
        padding: 12px 16px;
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        border-bottom-left-radius: 4px;
    }
    .chat-typing span {
        width: 8px;
        height: 8px;
        background: #16a34a;
        border-radius: 50%;
        animation: typing 1.4s infinite;
    }
    .chat-typing span:nth-child(2) { animation-delay: 0.2s; }
    .chat-typing span:nth-child(3) { animation-delay: 0.4s; }
    @keyframes typing {
        0%, 60%, 100% { opacity: 0.3; transform: scale(0.8); }
        30% { opacity: 1; transform: scale(1); }
    }

    #chatbot-footer {
        padding: 12px;
        border-top: 1px solid #e5e7eb;
        background: white;
        flex-shrink: 0;
    }
    #chatbot-model-select {
        width: 100%;
        margin-bottom: 8px;
        padding: 6px 10px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 12px;
        color: #374151;
        background: #f9fafb;
        outline: none;
    }
    #chatbot-model-select:focus { border-color: #16a34a; }

    #chatbot-input-row {
        display: flex;
        gap: 8px;
    }
    #chatbot-input {
        flex: 1;
        padding: 10px 14px;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        font-size: 13.5px;
        outline: none;
        transition: border-color 0.2s;
    }
    #chatbot-input:focus { border-color: #16a34a; box-shadow: 0 0 0 2px rgba(22,163,74,0.1); }
    #chatbot-input::placeholder { color: #9ca3af; }

    #chatbot-send {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: linear-gradient(135deg, #16a34a, #15803d);
        color: white;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: opacity 0.2s;
    }
    #chatbot-send:hover { opacity: 0.9; }
    #chatbot-send:disabled { opacity: 0.5; cursor: not-allowed; }
    #chatbot-send svg { width: 18px; height: 18px; }

    @media (max-width: 480px) {
        #chatbot-window {
            width: calc(100vw - 32px);
            right: 16px;
            bottom: 80px;
            max-height: 70vh;
        }
    }
</style>

{{-- Chat Bubble --}}
<button id="chatbot-bubble" onclick="toggleChatbot()" title="FTMS Assistant">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
    </svg>
</button>

{{-- Chat Window --}}
<div id="chatbot-window">
    <div id="chatbot-header">
        <div id="chatbot-header-title">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 0 0-2.455 2.456ZM16.894 20.567 16.5 21.75l-.394-1.183a2.25 2.25 0 0 0-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 0 0 1.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 0 0 1.423 1.423l1.183.394-1.183.394a2.25 2.25 0 0 0-1.423 1.423Z" />
            </svg>
            FTMS Assistant
        </div>
        <button id="chatbot-close" onclick="toggleChatbot()" title="Close">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="20" height="20">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <div id="chatbot-messages">
        <div class="chat-msg bot">
            Hello {{ auth()->user()->name }}! I'm the FTMS Assistant. I can help you with file tracking, movements, statuses, and general system questions. What would you like to know?
        </div>
    </div>

    <div id="chatbot-footer">
        {{-- <select id="chatbot-model-select" title="Select AI model">
            <option value="gpt-4o-mini">GPT-4o Mini (Fast)</option>
            <option value="gpt-4o">GPT-4o (Capable)</option>
            <option value="claude-3-5-sonnet">Claude Sonnet 4 (Balanced)</option>
            <option value="gpt-4.1-nano">GPT-4.1 Nano (Efficient)</option>
        </select> --}}
        <div id="chatbot-input-row">
            <input type="text" id="chatbot-input" placeholder="Ask about files, statuses, movements..." autocomplete="off" />
            <button id="chatbot-send" onclick="sendChatMessage()" title="Send">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                </svg>
            </button>
        </div>
    </div>
</div>

<script>
(function() {
    // User context from Blade
    const userContext = {
        name: @json(auth()->user()->name),
        role: @json(auth()->user()->isRegistryHead() ? 'Registry Head' : (auth()->user()->isRegistryStaff() ? 'Registry Staff' : 'Department User')),
        department: @json(auth()->user()->department ?? 'N/A'),
    };

    let systemData = null;
    let isOpen = false;
    let isProcessing = false;

    // Toggle chatbot window
    window.toggleChatbot = function() {
        const win = document.getElementById('chatbot-window');
        const bubble = document.getElementById('chatbot-bubble');
        isOpen = !isOpen;
        win.classList.toggle('open', isOpen);
        bubble.style.display = isOpen ? 'none' : 'flex';
        if (isOpen) {
            document.getElementById('chatbot-input').focus();
            if (!systemData) fetchSystemData();
        }
    };

    // Fetch live stats from server
    async function fetchSystemData() {
        try {
            const res = await fetch('/chatbot/system-data', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            });
            if (res.ok) {
                systemData = await res.json();
                console.log('[FTMS Chatbot] System data loaded:', systemData);
            }
        } catch (e) {
            console.warn('[FTMS Chatbot] Could not fetch system data:', e.message);
        }
    }

    // Build system prompt for Puter.js
    function buildSystemPrompt() {
        let prompt = `You are the FTMS Assistant — an AI helper for the File Tracking Management System (FTMS) used by the Ministry of Home Affairs (MOHA).

## Your Role
- Help users understand the file tracking system and how to use it
- Answer questions about files, movements, statuses, and workflows
- Provide current system statistics when asked
- Guide users through common tasks
- Be concise, helpful, and professional (under 200 words when possible)
- Use markdown formatting for clarity

## Current User
- Name: ${userContext.name}
- Role: ${userContext.role}
- Department: ${userContext.department}
`;

        if (systemData) {
            prompt += `
## Live System Statistics
- Total Files: ${systemData.files.total}
- Active Files: ${systemData.files.active}
- Files at Registry: ${systemData.files.at_registry}
- Files In Transit: ${systemData.files.in_transit}
- Overdue Files: ${systemData.files.overdue}
- Completed Files: ${systemData.files.completed}
- Under Review: ${systemData.files.under_review}
- Action Required: ${systemData.files.action_required}
- Total Movements: ${systemData.movements.total}
- Pending Movements: ${systemData.movements.pending}
- Recent Movements (7d): ${systemData.movements.recent_7_days}
- Active Employees: ${systemData.employees.total}
- Departments: ${systemData.organization.departments}
- Units: ${systemData.organization.units}
`;
        }

        prompt += `
## File Statuses
- at_registry: File is at the central registry
- in_transit: File has been sent, awaiting receipt
- received: File physically received but not confirmed
- under_review: File being reviewed/worked on
- action_required: File needs immediate action
- completed: All actions completed
- finished: Processing fully finished
- returned_to_registry: Returned to registry after processing
- archived: File archived, no longer active
- merged: File merged into another

## Movement Statuses
- sent: Dispatched but not yet delivered
- delivered: Physically delivered
- received: Receipt acknowledged
- acknowledged: Formally accepted
- rejected: File rejected by recipient

## File Workflow
1. Registration — Registry Head registers a new file
2. Send — Registry staff sends file to a department employee
3. In Transit — File physically moving
4. Receive — Recipient marks file as received
5. Confirm — Recipient acknowledges receipt
6. Action — Review and take action on the file
7. Complete/Return — File completed or returned to registry

## User Roles
- Admin: System admin, manages employees/departments (cannot access files)
- Registry Head: Manages registry, registers files, sends/receives
- Registry Staff: Assists with file operations at registry level
- Department User: Receives files, reviews, takes action, returns

## Key Features
- File Tracking: Track files by file number
- TJ Files: Temporary Jacket copies for parallel processing
- File Merging: Combine related files
- Change Recipient: Redirect in-transit files
- Sent Pending: View files pending receipt
- Overdue Alerts: Monitor files past due dates

## Rules
- Only answer FTMS-related questions
- If asked about something outside FTMS, politely redirect
- Keep responses concise and helpful`;

        return prompt;
    }

    // Generate response using Puter.js AI (primary)
    async function generatePuterResponse(message) {
        if (typeof puter === 'undefined' || !puter.ai) {
            throw new Error('Puter.js not available');
        }

        const model = document.getElementById('chatbot-model-select').value;
        const systemPrompt = buildSystemPrompt();

        const response = await puter.ai.chat(message, {
            model: model,
            systemPrompt: systemPrompt,
        });

        if (response && response.message && response.message.content) {
            return { text: response.message.content, source: 'Puter.js (' + model + ')' };
        }
        if (response && typeof response === 'string') {
            return { text: response, source: 'Puter.js (' + model + ')' };
        }
        // Handle array response format
        if (response && Array.isArray(response) && response.length > 0) {
            const firstResponse = response[0];
            if (firstResponse.message && firstResponse.message.content) {
                return { text: firstResponse.message.content, source: 'Puter.js (' + model + ')' };
            }
            if (firstResponse.text) {
                return { text: firstResponse.text, source: 'Puter.js (' + model + ')' };
            }
        }

        throw new Error('Unexpected Puter.js response format');
    }

    // Server-side OpenAI fallback
    async function generateServerResponse(message) {
        const res = await fetch('/chatbot', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ message: message }),
        });

        if (!res.ok) throw new Error('Server error: ' + res.status);
        const data = await res.json();
        return { text: data.response, source: 'OpenAI (' + (data.model || 'server') + ')' };
    }

    // Keyword-based offline fallback
    function getFallbackResponse(message) {
        const msg = message.toLowerCase();
        const responses = {
            'track file': 'To track a file, go to **File Tracking** from the navigation menu. Enter the file number (old or new format) to see its current location, holder, and complete movement history.',
            'send file': 'To send a file: Navigate to the file details, click **Send**, select the recipient employee, add optional notes, set the priority, and confirm. The file will be marked as "In Transit".',
            'receive file': 'To receive files: Go to **Receive Files** from the menu. You\'ll see all files sent to you that are pending receipt. Click **Receive** to confirm you have the physical file.',
            'confirm': 'To confirm a file: Go to **Confirm Files** from the menu. Select the file you received and click **Confirm** to formally acknowledge receipt.',
            'status': 'File statuses include: **At Registry** (held at registry), **In Transit** (being moved), **Received** (physically received), **Under Review** (being worked on), **Action Required** (needs attention), **Completed** (done), **Returned to Registry** (sent back).',
            'help': 'I can help you with: tracking files, understanding file statuses, sending/receiving files, navigating the dashboard, and general FTMS questions. Just ask!',
            'register': 'To register a new file: Only the **Registry Head** can register files. Go to **Create File**, enter the file subject, file numbers (old/new format), set priority and confidentiality, and submit.',
            'movement': 'File movements track every time a file changes hands. Each movement records: sender, recipient, timestamps, notes, and status. View movements from the file detail page.',
            'dashboard': 'Your dashboard shows: files in your possession, pending actions, recent movements, and statistics. Registry users see all files; department users see files relevant to their department.',
            'profile': 'Your profile page shows your employee details, department, position, and account settings.',
            'overdue': 'Overdue files are those that have passed their due date and are not yet completed or archived. Check your dashboard for overdue alerts.',
            'urgent': 'Urgent and Very Urgent files require expedited handling. They are highlighted on dashboards and in file lists.',
            'merge': 'File merging combines multiple related files into one. Only the **Registry Head** can merge files. Go to **Merge Files**, select the target file, choose files to merge, and confirm.',
            'tj': 'TJ (Temporary Jacket) files are temporary copies of an original file, allowing parallel processing in multiple departments.',
            'change recipient': 'If a file was sent to the wrong person, you can change the recipient while it is still "In Transit". Go to **Sent Pending** files and click **Change Recipient**.',
            'hello': 'Hello! I\'m the FTMS Assistant. How can I help you today? You can ask about file tracking, statuses, movements, or any FTMS feature.',
            'hi': 'Hi there! I\'m here to help with FTMS. Ask me anything about file tracking, sending/receiving files, or system features.',
            'thank': 'You\'re welcome! Let me know if you need anything else.',
        };

        // Check for keyword matches
        for (const [keyword, response] of Object.entries(responses)) {
            if (msg.includes(keyword)) {
                return response + '\n\n*(Offline mode — keyword response)*';
            }
        }

        // Stats response if data is available
        if (msg.includes('how many') || msg.includes('statistic') || msg.includes('count') || msg.includes('total')) {
            if (systemData) {
                return `Here are the current FTMS statistics:\n- **Total Files:** ${systemData.files.total}\n- **Active Files:** ${systemData.files.active}\n- **In Transit:** ${systemData.files.in_transit}\n- **Overdue:** ${systemData.files.overdue}\n- **Completed:** ${systemData.files.completed}\n- **Active Employees:** ${systemData.employees.total}\n- **Departments:** ${systemData.organization.departments}\n\n*(Offline mode — cached data)*`;
            }
        }

        return 'I\'m currently in offline mode and can only answer basic questions. Try asking about: **tracking files**, **sending files**, **receiving files**, **file statuses**, **movements**, **dashboard**, **TJ files**, or **merging files**.\n\n*(Offline mode)*';
    }

    // Simple markdown to HTML converter
    function renderMarkdown(text) {
        return text
            // Bold
            .replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
            // Italic
            .replace(/\*(.+?)\*/g, '<em>$1</em>')
            // Inline code
            .replace(/`(.+?)`/g, '<code>$1</code>')
            // Unordered lists
            .replace(/^- (.+)$/gm, '<li>$1</li>')
            .replace(/(<li>.*<\/li>\n?)+/g, '<ul>$&</ul>')
            // Ordered lists
            .replace(/^\d+\. (.+)$/gm, '<li>$1</li>')
            // Headers
            .replace(/^### (.+)$/gm, '<strong>$1</strong>')
            .replace(/^## (.+)$/gm, '<strong>$1</strong>')
            // Line breaks
            .replace(/\n\n/g, '<br><br>')
            .replace(/\n/g, '<br>');
    }

    // Add message to chat
    function addMessage(text, type, source) {
        const container = document.getElementById('chatbot-messages');
        const msg = document.createElement('div');
        msg.className = 'chat-msg ' + type;

        if (type === 'bot') {
            msg.innerHTML = renderMarkdown(text);
            if (source) {
                const srcEl = document.createElement('span');
                srcEl.className = 'msg-source';
                srcEl.textContent = source;
                msg.appendChild(srcEl);
            }
        } else {
            msg.textContent = text;
        }

        container.appendChild(msg);
        container.scrollTop = container.scrollHeight;
    }

    // Show/hide typing indicator
    function showTyping() {
        const container = document.getElementById('chatbot-messages');
        const typing = document.createElement('div');
        typing.className = 'chat-typing';
        typing.id = 'chatbot-typing';
        typing.innerHTML = '<span></span><span></span><span></span>';
        container.appendChild(typing);
        container.scrollTop = container.scrollHeight;
    }
    function hideTyping() {
        const el = document.getElementById('chatbot-typing');
        if (el) el.remove();
    }

    // Main send function
    window.sendChatMessage = async function() {
        const input = document.getElementById('chatbot-input');
        const message = input.value.trim();
        if (!message || isProcessing) return;

        isProcessing = true;
        input.value = '';
        document.getElementById('chatbot-send').disabled = true;

        addMessage(message, 'user');
        showTyping();

        let response = null;

        // Try Puter.js first (primary, free)
        try {
            response = await generatePuterResponse(message);
        } catch (puterErr) {
            console.warn('[FTMS Chatbot] Puter.js failed:', puterErr.message);

            // Try server-side OpenAI fallback
            try {
                response = await generateServerResponse(message);
            } catch (serverErr) {
                console.warn('[FTMS Chatbot] Server fallback failed:', serverErr.message);

                // Use keyword fallback (offline mode)
                const fallbackText = getFallbackResponse(message);
                response = { text: fallbackText, source: 'Offline' };
            }
        }

        hideTyping();

        if (response) {
            addMessage(response.text, 'bot', response.source);
        }

        isProcessing = false;
        document.getElementById('chatbot-send').disabled = false;
        input.focus();
    };

    // Enter key to send
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('chatbot-input');
        if (input) {
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    sendChatMessage();
                }
            });
        }
    });
})();
</script>

@endif
@endauth
