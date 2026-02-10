<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Employee extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = 'employee_number';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'employee_number',
        'name',
        'email',
        'password',
        'office',
        'role',
        'is_active',
        'created_by',
        'position_id',
        'department_id',
        'unit_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    public function getAuthIdentifierName()
    {
        return 'employee_number';
    }

    public function username()
    {
        return 'employee_number';
    }

    // Position relationship
    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    // Department relationship (direct assignment)
    public function departmentRel()
    {
        return $this->belongsTo(Department::class);
    }

    // Unit relationship (direct assignment)
    public function unitRel()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    // Get effective department (unit's department if in unit, otherwise direct department)
    public function getEffectiveDepartment()
    {
        // If employee belongs to a unit, get the unit's department
        if ($this->unit_id && $this->relationLoaded('unitRel')) {
            return $this->unitRel?->departmentRel;
        }
        
        // Otherwise get the direct department
        if ($this->department_id) {
            if ($this->relationLoaded('departmentRel')) {
                return $this->departmentRel;
            }
            return Department::find($this->department_id);
        }
        
        return null;
    }

    // Employment helpers
    public function isEmployedInUnit(): bool
    {
        return !is_null($this->unit_id);
    }

    public function isEmployedInDepartment(): bool
    {
        return !is_null($this->department_id) && is_null($this->unit_id);
    }

    public function isHeadOfDepartment(): bool
    {
        if (!$this->position_id) {
            return false;
        }
        
        return DepartmentHead::where('position_id', $this->position_id)
            ->where('is_active', true)
            ->exists();
    }

    public function isHeadOfUnit(): bool
    {
        if (!$this->position_id) {
            return false;
        }
        
        return UnitHead::where('position_id', $this->position_id)
            ->where('is_active', true)
            ->exists();
    }

    public function isManagement(): bool
    {
        if (!$this->position_id) {
            return false;
        }
        return Position::where('id', $this->position_id)->value('is_management') ?? false;
    }

    // Legacy relationships (for backward compatibility)
    public function organizationalUnit()
    {
        return $this->unitRel ?? $this->departmentRel;
    }

    public function creator()
    {
        return $this->belongsTo(Employee::class, 'created_by', 'employee_number');
    }

    public function createdUsers()
    {
        return $this->hasMany(Employee::class, 'created_by', 'employee_number');
    }

    public function filesRegistered()
    {
        return $this->hasMany(File::class, 'registered_by', 'employee_number');
    }

    public function filesHeld()
    {
        return $this->hasMany(File::class, 'current_holder', 'employee_number');
    }

    public function sentMovements()
    {
        return $this->hasMany(FileMovement::class, 'sender_emp_no', 'employee_number');
    }

    public function receivedMovements()
    {
        return $this->hasMany(FileMovement::class, 'actual_receiver_emp_no', 'employee_number');
    }

    public function pendingReceipts()
    {
        return $this->hasMany(FileMovement::class, 'intended_receiver_emp_no', 'employee_number')
            ->where('movement_status', 'sent');
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class, 'employee_number', 'employee_number');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInDepartment($query, $departmentId)
    {
        return $query->where('department_id', $departmentId);
    }

    public function scopeInUnit($query, $unitId)
    {
        return $query->where('unit_id', $unitId);
    }

    public function scopeInDepartmentOrUnit($query, $departmentId)
    {
        return $query->where(function ($q) use ($departmentId) {
            $q->where('department_id', $departmentId)
              ->orWhereHas('unitRel', function ($q2) use ($departmentId) {
                  $q2->where('department_id', $departmentId);
              });
        });
    }

    // Accessors for backward compatibility
    public function getDepartmentAttribute()
    {
        $dept = $this->getEffectiveDepartment();
        return $dept?->name;
    }

    public function getUnitAttribute()
    {
        if ($this->unit_id) {
            return Unit::where('id', $this->unit_id)->value('name');
        }
        return null;
    }

    public function getPositionTitleAttribute()
    {
        if ($this->position_id) {
            return Position::where('id', $this->position_id)->value('title');
        }
        return null;
    }

    // Role-based access
    public function isRegistryHead()
    {
        return $this->role === 'registry_head';
    }

    public function isRegistryClerk()
    {
        return $this->role === 'registry_clerk';
    }

    public function isRegistryStaff()
    {
        return $this->isRegistryHead() || $this->isRegistryClerk();
    }

    public function isDepartmentHeadRole()
    {
        return $this->role === 'department_head';
    }

    public function canRegisterFiles()
    {
        return $this->isRegistryHead();
    }

    public function canManageUsers()
    {
        return $this->isRegistryHead();
    }

    public function canSendFiles()
    {
        return $this->is_active;
    }

    public function canReceiveFiles()
    {
        return $this->is_active;
    }

    public function canTrackFiles()
    {
        return $this->is_active;
    }

    public function getRoleBadgeColor()
    {
        return match ($this->role) {
            'registry_head' => 'purple',
            'registry_clerk' => 'blue',
            'department_head' => 'yellow',
            default => 'gray'
        };
    }
}
