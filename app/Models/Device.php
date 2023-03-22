<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Device extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'device';

    protected $fillable = [
        'device_name',
        'device_code',
        'created_by',
    ];

    public function creator(){
        return $this->belongsTo('App\Models\User', 'created_by', 'id')->withTrashed();
    }

    public function socket(){
        return $this->hasMany('App\Models\DeviceSocket', 'device_id', 'id');    
    }
}
