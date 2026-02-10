<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'subject',
        'file_title',
        'old_file_no',
        'new_file_no',
        'original_file_no',
        'is_copy',
        'copy_number',
        'priority',
        'status',
        'confidentiality',
        'remarks',
        'due_date',
        'date_registered',
        'current_holder',
        'registered_by',
    ];

    protected $casts = [
        'due_date' => 'date',
        'date_registered' => 'date',
    ];

    // Relationships
    public function currentHolder()
    {
        return $this->belongsTo(Employee::class, 'current_holder', 'employee_number');
    }

    public function registeredBy()
    {
        return $this->belongsTo(Employee::class, 'registered_by', 'employee_number');
    }

    public function movements()
    {
        return $this->hasMany(FileMovement::class)->orderBy('sent_at', 'desc');
    }

    public function attachments()
    {
        return $this->hasMany(FileAttachment::class);
    }

    public function latestMovement()
    {
        return $this->hasOne(FileMovement::class)->latestOfMany();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->whereNotIn('status', ['archived', 'completed']);
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
            ->whereNotIn('status', ['completed', 'archived']);
    }

    public function scopeInDepartment($query, $department)
    {
        return $query->whereHas('currentHolder', function ($q) use ($department) {
            $q->whereHas('organizationalUnit', function ($q2) use ($department) {
                $q2->where('name', $department);
            });
        });
    }

    public function scopeOriginal($query)
    {
        return $query->where('is_copy', false);
    }

    public function scopeCopies($query)
    {
        return $query->where('is_copy', true);
    }

    public function scopeOfOriginal($query, $originalFileNo)
    {
        return $query->where('original_file_no', $originalFileNo);
    }

    public function scopeAccessibleBy($query, Employee $employee)
    {
        if ($employee->isRegistryStaff()) {
            return $query;
        }

        return $query->where(function ($q) use ($employee) {
            $q->where('confidentiality', 'public')
                ->orWhereHas('currentHolder', function ($q2) use ($employee) {
                    $q2->whereHas('organizationalUnit', function ($q3) use ($employee) {
                        $q3->where('name', $employee->department);
                    });
                });
        });
    }

    // Helper Methods
    public function getStatusLabel()
    {
        return match ($this->status) {
            'in_transit' => 'Awaiting Receipt',
            default => ucwords(str_replace('_', ' ', $this->status))
        };
    }

    public function getStatusBadgeColor()
    {
        return match ($this->status) {
            'at_registry' => 'green',
            'in_transit' => 'yellow',
            'received' => 'blue',
            'under_review' => 'purple',
            'action_required' => 'red',
            'completed' => 'gray',
            'finished' => 'green',
            'returned_to_registry' => 'green',
            'archived' => 'gray',
            default => 'gray'
        };
    }

    public function getPriorityBadgeColor()
    {
        return match ($this->priority) {
            'urgent' => 'red',
            'very_urgent' => 'purple',
            default => 'blue'
        };
    }

    public function getConfidentialityBadgeColor()
    {
        return match ($this->confidentiality) {
            'secret' => 'red',
            'confidential' => 'yellow',
            default => 'green'
        };
    }

    public function isOverdue()
    {
        return $this->due_date
            && $this->due_date->isPast()
            && ! in_array($this->status, ['completed', 'archived']);
    }

    public function getDaysOverdue()
    {
        if (! $this->due_date || ! $this->due_date->isPast()) {
            return 0;
        }

        return $this->due_date->diffInDays(now());
    }

    public function getDisplayFileNo()
    {
        if ($this->is_copy && $this->copy_number) {
            return $this->original_file_no.'-copy'.$this->copy_number;
        }

        return $this->new_file_no;
    }

    public function getCopies()
    {
        return static::where('original_file_no', $this->new_file_no)
            ->where('is_copy', true)
            ->orderBy('copy_number')
            ->get();
    }

    public function getNextCopyNumber()
    {
        $maxCopy = static::where('original_file_no', $this->new_file_no)
            ->max('copy_number');

        return ($maxCopy ?? 0) + 1;
    }

    public static function generateCopyFileNo($originalFileNo)
    {
        $file = static::where('new_file_no', $originalFileNo)->first();
        if (! $file) {
            return null;
        }
        $nextCopyNumber = $file->getNextCopyNumber();

        return $originalFileNo.'-copy'.$nextCopyNumber;
    }

    public function canBeSentBy(Employee $employee)
    {
        // Any file at registry can be sent by anyone
        if ($this->status === 'at_registry') {
            return true;
        }

        // User must be current holder for files outside registry
        if ($this->current_holder !== $employee->employee_number) {
            return false;
        }

        // Registry staff can send files at certain statuses
        if ($employee->isRegistryStaff()) {
            return in_array($this->status, ['received', 'under_review', 'returned_to_registry']);
        }

        // Department users can send files they have received
        return in_array($this->status, ['received', 'under_review']);
    }
}
