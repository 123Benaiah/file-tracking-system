<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileAttachment extends Model
{
    protected $fillable = [
        'file_id',
        'filename',
        'original_name',
        'path',
        'mime_type',
        'size',
        'uploaded_by',
        'description',
    ];

    public function uploader()
    {
        return $this->belongsTo(Employee::class, 'uploaded_by', 'employee_number');
    }

    public function file()
    {
        return $this->belongsTo(File::class);
    }
}
