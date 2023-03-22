<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Softdeletes;

class DeviceSocketReading extends Model
{
    use HasFactory, Softdeletes;

    protected $table = 'socket_reading';

    protected $fillable = [
        'socket_id',
        'kwh',
        'variance_kwh',
        'treg'
    ];
}
