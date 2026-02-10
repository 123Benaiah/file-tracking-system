<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrganizationalUnit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'institution',
        'parent_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function parent()
    {
        return $this->belongsTo(OrganizationalUnit::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(OrganizationalUnit::class, 'parent_id');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
