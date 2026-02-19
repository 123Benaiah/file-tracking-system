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
        'gender',
        'email',
        'password',
        'office',
        'role',
        'is_active',
        'is_registry_head',
        'is_registry_staff',
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
        'is_registry_head' => 'boolean',
        'is_registry_staff' => 'boolean',
    ];

    public function getFormalNameAttribute(): string
    {
        $prefix = match ($this->gender) {
            'male' => 'Mr.',
            'female' => 'Ms.',
            default => '',
        };

        return $prefix ? "{$prefix} {$this->name}" : $this->name;
    }

    public function getAuthIdentifierName()
    {
        return 'employee_number';
    }

    public function username()
    {
        return 'employee_number';
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function departmentRel()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function unitRel()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function getEffectiveDepartment()
    {
        if ($this->unit_id) {
            if (!$this->relationLoaded('unitRel')) {
                $this->load('unitRel.department');
            }
            return $this->unitRel?->department;
        }

        if ($this->department_id) {
            if (!$this->relationLoaded('departmentRel')) {
                $this->load('departmentRel');
            }
            return $this->departmentRel;
        }

        return null;
    }

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
        if (!$this->relationLoaded('position')) {
            $this->load('position');
        }
        return $this->position?->is_management ?? false;
    }

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

    public function getDepartmentAttribute()
    {
        $dept = $this->getEffectiveDepartment();
        return $dept?->name;
    }

    public function getUnitAttribute()
    {
        if ($this->unit_id) {
            if (!$this->relationLoaded('unitRel')) {
                $this->load('unitRel');
            }
            return $this->unitRel?->name;
        }
        return null;
    }

    public function getPositionTitleAttribute()
    {
        if ($this->position_id) {
            if (!$this->relationLoaded('position')) {
                $this->load('position');
            }
            return $this->position?->title;
        }
        return null;
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isRegistryHead(): bool
    {
        // Use flag-based check first
        if ($this->is_registry_head) {
            return true;
        }

        // Fallback: check department/unit/position (deprecated, for backward compatibility)
        if (!$this->unit_id || !$this->department_id || !$this->position_id) {
            return false;
        }

        if (!$this->relationLoaded('unitRel')) {
            $this->load(['unitRel']);
        }
        if (!$this->relationLoaded('departmentRel')) {
            $this->load(['departmentRel']);
        }
        if (!$this->relationLoaded('position')) {
            $this->load(['position']);
        }

        $unit = $this->unitRel;
        $department = $this->departmentRel;
        $position = $this->position;

        if (!$unit || !$department || !$position) {
            return false;
        }

        $deptName = strtolower($department->name);
        $deptCode = strtolower($department->code);
        $isHRA = $deptName === 'human resources and administration' || $deptCode === 'hra';

        $isRegistryUnit = strtolower($unit->name) === 'registry';

        $positionTitle = strtolower($position->title ?? '');
        $isRegistryHeadPosition = strpos($positionTitle, 'registry head') !== false;

        return $isHRA && $isRegistryUnit && $isRegistryHeadPosition;
    }

    public function isRegistryStaff(): bool
    {
        // Use flag-based check first
        if ($this->is_registry_staff) {
            return true;
        }

        return $this->isInRegistryUnit();
    }

    public function isInRegistryUnit(): bool
    {
        // Use flag-based check first
        if ($this->relationLoaded('unitRel') && $this->unitRel?->is_registry_unit) {
            return true;
        }

        if (!$this->unit_id) {
            return false;
        }

        if (!$this->relationLoaded('unitRel')) {
            $this->load(['unitRel']);
        }

        $unit = $this->unitRel;
        if (!$unit) {
            return false;
        }

        // Check flag first
        if ($unit->is_registry_unit) {
            return true;
        }

        // Fallback: check unit name (deprecated)
        if (strtolower($unit->name) !== 'registry') {
            return false;
        }

        if (!$this->department_id) {
            return false;
        }

        if (!$this->relationLoaded('departmentRel')) {
            $this->load(['departmentRel']);
        }

        $department = $this->departmentRel;
        if (!$department) {
            return false;
        }

        // Check flag first
        if ($department->is_registry_department) {
            return true;
        }

        // Fallback: check department name/code (deprecated)
        $deptName = strtolower($department->name);
        $deptCode = strtolower($department->code);

        return $deptName === 'human resources and administration' || $deptCode === 'hra';
    }

    public function canResendFiles(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->isRegistryHead()) {
            return true;
        }

        return $this->isInRegistryUnit();
    }

    public function canRegisterFiles(): bool
    {
        return $this->isAdmin() || $this->isRegistryStaff();
    }

    public function canManageUsers(): bool
    {
        return $this->isAdmin() || $this->isRegistryStaff();
    }

    public function canManageEmployees(): bool
    {
        return $this->isAdmin();
    }

    public function canAccessAdminPanel(): bool
    {
        return $this->isAdmin();
    }

    public function canAccessRegistry(): bool
    {
        return $this->isAdmin() || $this->isRegistryStaff();
    }

    public function canAccessDepartment(): bool
    {
        return $this->is_active && !$this->isRegistryHead();
    }

    public function canSendFiles(): bool
    {
        return $this->is_active;
    }

    public function canReceiveFiles(): bool
    {
        return $this->is_active;
    }

    public function canTrackFiles(): bool
    {
        return $this->is_active;
    }

    public function getRoleBadgeColor(): string
    {
        return match ($this->role) {
            'admin' => 'red',
            default => 'gray'
        };
    }

    public function getRoleLabel(): string
    {
        if ($this->isRegistryHead()) {
            return 'Registry Head';
        }
        
        return match ($this->role) {
            'admin' => 'Administrator',
            'user' => 'User',
            default => 'Unknown'
        };
    }
}
