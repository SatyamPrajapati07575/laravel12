<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BulkUpload extends Model
{
    protected $fillable = [
        'file_path',
        'status',
        'success_count',
        'fail_count',
        'error_log',
    ];
}
