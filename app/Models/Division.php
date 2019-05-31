<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Division extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'name','status','pic_user_id'
    ];

    public static function allDataCreate(){
        return ['users'=>User::all(['id','name'])->makeVisible('id')];
    }

    public function allDataUpdate(){
        $users = $this->users->makeVisible('id');
        $users = $users->map(function ($user) {
            return ['id' => $user['id'], 'name' => $user['name']];
        });
        return ['users'=>$users];
    }

    public function pic_user(){
        return $this->belongsTo('App\Models\User','pic_user_id','id');
    }

    public function users(){
        return $this->hasMany('App\Models\User');
    }
}
