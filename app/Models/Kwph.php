<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Softdeletes;

class Kwph extends Model
{
    use HasFactory, Softdeletes;

    protected $table = 'kwph';

    protected $fillable = [
        'kwph',
        'created_by'
    ];
}