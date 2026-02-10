<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'description',
        'location',
        'has_units',
    ];

    protected $casts = [
        'has_units' => 'boolean',
    ];

    // Relationships
    public function units()
    {
        return $this->hasMany(Unit::class);
    }

    public function headPosition()
    {
        return $this->hasOne(DepartmentHead::class)->where('is_active', true);
    }

    public function departmentHead()
    {
        return $this->hasOneThrough(
            Employee::class,
            DepartmentHead::class,
            'department_id',
            'employee_number',
            'id',
            'position_id'
        )->where('department_heads.is_active', true);
    }

    public function allEmployees()
    {
        return $this->hasMany(Employee::class, 'department_id');
    }

    public function employees()
    {
        // Only employees directly assigned to this department (not through units)
        return $this->hasMany(Employee::class, 'department_id')
            ->whereNull('unit_id');
    }

    public function employeesInDepartment()
    {
        // All employees under this department (direct + unit employees)
        return Employee::where('department_id', $this->id)
            ->orWhereHas('unitRel', function ($query) {
                $query->where('department_id', $this->id);
            });
    }

    // Helper methods
    public function getHeadAttribute()
    {
        return $this->departmentHead;
    }

    public function getUnitCountAttribute()
    {
        return $this->units()->count();
    }

    public function getEmployeeCountAttribute()
    {
        return $this->employeesInDepartment()->count();
    }

    public function hasUnits(): bool
    {
        return $this->has_units && $this->units()->exists();
    }

    public function isDirectorPosition(Position $position): bool
    {
        return $position->position_type === 'director';
    }
}
