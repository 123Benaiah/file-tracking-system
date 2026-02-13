<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'description',
        'department_id',
        'location',
        'is_registry_unit',
    ];

    protected $casts = [
        'is_registry_unit' => 'boolean',
    ];

    // Relationships
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function headPosition()
    {
        return $this->hasOne(UnitHead::class)->where('is_active', true);
    }

    public function unitHead()
    {
        return $this->hasOneThrough(
            Employee::class,
            UnitHead::class,
            'unit_id',
            'employee_number',
            'id',
            'position_id'
        )->where('unit_heads.is_active', true);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function allEmployees()
    {
        return $this->employees;
    }

    // Helper methods
    public function getHeadAttribute()
    {
        return $this->unitHead;
    }

    public function getEmployeeCountAttribute()
    {
        return $this->employees()->count();
    }

    public function isAssistantDirectorPosition(Position $position): bool
    {
        return $position->position_type === 'assistant_director';
    }
}
