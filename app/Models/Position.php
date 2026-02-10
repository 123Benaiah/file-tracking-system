<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Position extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'code',
        'description',
        'position_type',
        'level',
        'employment_type',
    ];

    protected $casts = [
        'level' => 'integer',
    ];

    const TYPE_DIRECTOR = 'director';
    const TYPE_ASSISTANT_DIRECTOR = 'assistant_director';
    const TYPE_SUPERVISOR = 'supervisor';
    const TYPE_STAFF = 'staff';
    const TYPE_SUPPORT = 'support';

    const EMPLOYMENT_PERMANENT = 'permanent';
    const EMPLOYMENT_CONTRACT = 'contract';
    const EMPLOYMENT_TEMPORARY = 'temporary';
    const EMPLOYMENT_INTERN = 'intern';

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function asDepartmentHead()
    {
        return $this->hasMany(DepartmentHead::class);
    }

    public function asUnitHead()
    {
        return $this->hasMany(UnitHead::class);
    }

    public function getIsDirectorAttribute(): bool
    {
        return $this->position_type === self::TYPE_DIRECTOR;
    }

    public function getIsAssistantDirectorAttribute(): bool
    {
        return $this->position_type === self::TYPE_ASSISTANT_DIRECTOR;
    }

    public function getIsManagementAttribute(): bool
    {
        return in_array($this->position_type, [self::TYPE_DIRECTOR, self::TYPE_ASSISTANT_DIRECTOR]);
    }

    public function scopeDirectors($query)
    {
        return $query->where('position_type', self::TYPE_DIRECTOR);
    }

    public function scopeAssistantDirectors($query)
    {
        return $query->where('position_type', self::TYPE_ASSISTANT_DIRECTOR);
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->position_type) {
            self::TYPE_DIRECTOR => 'Director',
            self::TYPE_ASSISTANT_DIRECTOR => 'Assistant Director',
            self::TYPE_SUPERVISOR => 'Supervisor',
            self::TYPE_STAFF => 'Staff',
            self::TYPE_SUPPORT => 'Support',
            default => ucfirst($this->position_type),
        };
    }
}
