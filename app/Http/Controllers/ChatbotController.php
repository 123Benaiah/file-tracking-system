<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\File;
use App\Models\FileMovement;
use App\Models\Unit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    /**
     * Return live system statistics as JSON for the frontend Puter.js AI.
     */
    public function getSystemData(): JsonResponse
    {
        $totalFiles = File::count();
        $activeFiles = File::active()->count();
        $inTransitFiles = File::where('status', 'in_transit')->count();
        $overdueFiles = File::overdue()->count();
        $completedFiles = File::where('status', 'completed')->count();
        $atRegistryFiles = File::where('status', 'at_registry')->count();
        $receivedFiles = File::where('status', 'received')->count();
        $underReviewFiles = File::where('status', 'under_review')->count();
        $actionRequiredFiles = File::where('status', 'action_required')->count();
        $archivedFiles = File::where('status', 'archived')->count();

        $totalMovements = FileMovement::count();
        $pendingMovements = FileMovement::where('movement_status', 'sent')->count();
        $overdueMovements = FileMovement::where('is_overdue', true)->count();
        $recentMovements = FileMovement::recent(7)->count();

        $totalEmployees = Employee::active()->count();
        $departmentCount = Department::count();
        $unitCount = Unit::count();

        return response()->json([
            'files' => [
                'total' => $totalFiles,
                'active' => $activeFiles,
                'in_transit' => $inTransitFiles,
                'overdue' => $overdueFiles,
                'completed' => $completedFiles,
                'at_registry' => $atRegistryFiles,
                'received' => $receivedFiles,
                'under_review' => $underReviewFiles,
                'action_required' => $actionRequiredFiles,
                'archived' => $archivedFiles,
            ],
            'movements' => [
                'total' => $totalMovements,
                'pending' => $pendingMovements,
                'overdue' => $overdueMovements,
                'recent_7_days' => $recentMovements,
            ],
            'employees' => [
                'total' => $totalEmployees,
            ],
            'organization' => [
                'departments' => $departmentCount,
                'units' => $unitCount,
            ],
            'timestamp' => now()->toISOString(),
        ]);
    }

    /**
     * Build the FTMS-specific system prompt with live database statistics.
     */
    public function getSystemPrompt(): string
    {
        $stats = [
            'total_files' => File::count(),
            'active_files' => File::active()->count(),
            'in_transit' => File::where('status', 'in_transit')->count(),
            'overdue_files' => File::overdue()->count(),
            'completed_files' => File::where('status', 'completed')->count(),
            'at_registry' => File::where('status', 'at_registry')->count(),
            'total_movements' => FileMovement::count(),
            'pending_movements' => FileMovement::where('movement_status', 'sent')->count(),
            'total_employees' => Employee::active()->count(),
            'departments' => Department::count(),
            'units' => Unit::count(),
        ];

        $config = config('chatbot');

        $statusDefinitions = collect($config['file_statuses'])
            ->map(fn($desc, $status) => "- **{$status}**: {$desc}")
            ->implode("\n");

        $movementStatuses = collect($config['movement_statuses'])
            ->map(fn($desc, $status) => "- **{$status}**: {$desc}")
            ->implode("\n");

        $workflowSteps = implode("\n", $config['workflow']['steps']);

        $roles = collect($config['roles'])
            ->map(fn($desc, $role) => "- **{$role}**: {$desc}")
            ->implode("\n");

        return <<<PROMPT
You are the FTMS Assistant — an AI helper for the File Tracking Management System (FTMS) used by the Ministry of Home Affairs (MOHA).

## Your Role
- Help users understand the file tracking system and how to use it
- Answer questions about files, movements, statuses, and workflows
- Provide current system statistics when asked
- Guide users through common tasks (tracking, sending, receiving files)
- Be concise, helpful, and professional

## Current System Statistics (Live)
- Total Files: {$stats['total_files']}
- Active Files: {$stats['active_files']}
- Files at Registry: {$stats['at_registry']}
- Files In Transit: {$stats['in_transit']}
- Overdue Files: {$stats['overdue_files']}
- Completed Files: {$stats['completed_files']}
- Total Movements: {$stats['total_movements']}
- Pending Movements: {$stats['pending_movements']}
- Active Employees: {$stats['total_employees']}
- Departments: {$stats['departments']}
- Units: {$stats['units']}

## File Statuses
{$statusDefinitions}

## Movement Statuses
{$movementStatuses}

## File Workflow
{$workflowSteps}

## User Roles
{$roles}

## Important Rules
- Only answer questions related to FTMS and file tracking
- If asked about something outside FTMS, politely redirect to FTMS topics
- Use markdown formatting for clarity
- Keep responses concise (under 200 words when possible)
- If you don't know something specific about the system, say so honestly
PROMPT;
    }

    /**
     * Handle a chatbot message via the OpenAI API (server-side fallback).
     */
    public function handleMessage(Request $request): JsonResponse
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $apiKey = config('chatbot.openai.api_key');

        if (empty($apiKey)) {
            return response()->json([
                'response' => 'Server-side AI is not configured. Please use the client-side AI or try again later.',
                'source' => 'error',
            ], 503);
        }

        try {
            $systemPrompt = $this->getSystemPrompt();

            // Add user context if authenticated
            if (auth()->check()) {
                $user = auth()->user();
                $systemPrompt .= "\n\n## Current User Context\n";
                $systemPrompt .= "- Name: {$user->name}\n";
                $systemPrompt .= '- Role: '.($user->isRegistryHead() ? 'Registry Head' : ($user->isRegistryStaff() ? 'Registry Staff' : 'Department User'))."\n";
                if ($user->department) {
                    $systemPrompt .= "- Department: {$user->department}\n";
                }
            }

            $certPath = public_path('assets/certification/cacert.pem');

            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$apiKey,
                'Content-Type' => 'application/json',
            ])
                ->withOptions(file_exists($certPath) ? ['verify' => $certPath] : [])
                ->timeout(30)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => config('chatbot.openai.model', 'gpt-4o-mini'),
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $request->input('message')],
                    ],
                    'max_tokens' => config('chatbot.openai.max_tokens', 500),
                    'temperature' => config('chatbot.openai.temperature', 0.7),
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $reply = $data['choices'][0]['message']['content'] ?? 'No response generated.';

                return response()->json([
                    'response' => $reply,
                    'source' => 'openai',
                    'model' => config('chatbot.openai.model', 'gpt-4o-mini'),
                ]);
            }

            Log::warning('OpenAI API error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return response()->json([
                'response' => 'AI service temporarily unavailable. Please try again.',
                'source' => 'error',
            ], 502);
        } catch (\Exception $e) {
            Log::error('Chatbot error', ['error' => $e->getMessage()]);

            return response()->json([
                'response' => 'An error occurred while processing your request.',
                'source' => 'error',
            ], 500);
        }
    }
}
