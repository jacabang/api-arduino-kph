<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeviceSocket extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'socket';

    protected $fillable = [
        'device_id',
        'socket_name',
        'socket_code',
        'current_kwh',
        'created_by',
    ];

    public function device(){
        return $this->belongsTo('App\Models\Device', 'device_id', 'id');
    }

    public function creator(){
        return $this->belongsTo('App\Models\User', 'created_by', 'id')->withTrashed();
    }

    public function readings(){
        return $this->hasMany('App\Models\DeviceSocketReading', 'socket_id', 'id')->orderBy('treg','DESC');    
    }

    public function reading(){
        return $this->hasOne('App\Models\DeviceSocketReading', 'socket_id', 'id')->orderBy('treg','DESC');    
    }
}
