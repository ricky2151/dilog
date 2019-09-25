<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Division extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'name','mr_enable'
    ];

    public function allDataUpdate(){
        $users = $this->users->makeVisible('id');
        $users = $users->map(function ($user) {
            return ['id' => $user['id'], 'name' => $user['name']];
        });
        return ['users'=>$users];
    }

    public function users(){
        return $this->hasMany('App\Models\User');
    }

    public function materialRequest(){
        return $this->hasMany('App\Models\MaterialRequest');
    }
}
