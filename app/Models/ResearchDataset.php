<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResearchDataset extends Model
{
    protected $fillable = [
        'title', 'dataset_period', 'access_level',
        'data_json', 'department_scope', 'sample_size',
        'description', 'file_path',
    ];

    protected $casts = [
        'data_json' => 'array',
    ];
}
