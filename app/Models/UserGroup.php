<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserGroup extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'user_group';

    protected $fillable = [
        'user_group',
        'access',
        'editable',
        'created_by',
    ];

    public function users(){
        return $this->hasMany('App\Models\User', 'user_group_id', 'id');    
    }

    public function creator(){
        return $this->belongsTo('App\Models\User', 'created_by', 'id')->withTrashed();
    }
}
