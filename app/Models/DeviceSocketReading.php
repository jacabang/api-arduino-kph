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
        'kwph',
        'variance_kwh',
        'treg'
    ];

    public function socket(){
        return $this->belongsTo('App\Models\DeviceSocket', 'socket_id', 'id');
    }
}
