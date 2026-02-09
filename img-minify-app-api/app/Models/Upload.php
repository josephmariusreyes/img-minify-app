<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    protected $table = 'uploads';

    // Primary key config
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    // Because your columns are strings, not timestamps
    public $timestamps = false;

    // Mass-assignable fields
    protected $fillable = [
        'email',
        'upload_status',
        'created_at',
        'updated_at',
        'upload_metadata',
    ];

    // Auto-cast JSONB → array
    protected $casts = [
        'upload_metadata' => 'array',
    ];
}
