<?php

namespace App\Models;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = [
        'task_id',
        'name',
        'path',
        'size',
        'mime_type'
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}

