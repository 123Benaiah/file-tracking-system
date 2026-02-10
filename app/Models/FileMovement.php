<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_id',
        'sender_emp_no',
        'intended_receiver_emp_no',
        'actual_receiver_emp_no',
        'hand_carried_by',
        'delivery_method',
        'sender_comments',
        'receiver_comments',
        'sent_at',
        'received_at',
        'acknowledged_at',
        'movement_status',
        'sla_days',
        'is_overdue',
        'qr_code',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'received_at' => 'datetime',
        'acknowledged_at' => 'datetime',
        'is_overdue' => 'boolean',
    ];

    // Relationships
    public function file()
    {
        return $this->belongsTo(File::class);
    }

    public function sender()
    {
        return $this->belongsTo(Employee::class, 'sender_emp_no', 'employee_number');
    }

    public function intendedReceiver()
    {
        return $this->belongsTo(Employee::class, 'intended_receiver_emp_no', 'employee_number');
    }

    public function actualReceiver()
    {
        return $this->belongsTo(Employee::class, 'actual_receiver_emp_no', 'employee_number');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('movement_status', 'sent');
    }

    public function scopeOverdue($query)
    {
        return $query->where('is_overdue', true);
    }

    public function scopeRecent($query, $days = 7)
    {
        return $query->where('sent_at', '>=', now()->subDays($days));
    }

    // Helper Methods
    public function calculateOverdue()
    {
        if (!$this->received_at && $this->sent_at) {
            $dueDate = $this->sent_at->addDays($this->sla_days);
            $this->is_overdue = now()->greaterThan($dueDate);
            $this->save();
        }
        return $this->is_overdue;
    }

    public function getTransitTime()
    {
        if ($this->received_at) {
            return $this->sent_at->diff($this->received_at);
        }
        return null;
    }

    public function getStatusBadgeColor()
    {
        return match($this->movement_status) {
            'sent' => 'yellow',
            'delivered' => 'blue',
            'received' => 'green',
            'acknowledged' => 'purple',
            'rejected' => 'red',
            default => 'gray'
        };
    }
}
