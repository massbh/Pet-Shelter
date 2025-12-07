<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chart extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'chart_type',
        'data_source',
        'config'
    ];

    protected $casts = [
        'config' => 'array'
    ];
}
