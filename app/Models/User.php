<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements JWTSubject
{
    use Notifiable,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',"job_title","warehouse_id","role_id","division_id"
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','id'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    // protected $casts = [
    //     'email_verified_at' => 'datetime',
    // ];


    public static function findEmail($email){
        $user = User::where("email",$email)->first();
        return ($user != null ? $user : false);
    }

    public static function isEmailUserAvailable($email){
        $user = User::where("email",$email)->first();
        return (is_null($user) == false ? true : false);
    }


    public function getJWTIdentifier() {

        return $this->getKey();

    }

    public function getJWTCustomClaims() {

        return [];

    }

    public function division()
    {
        return $this->belongsTo('App\Models\Division');
    }

    public function role()
    {
        return $this->belongsTo('App\Models\Role');
    }

    public function warehouse()
    {
        return $this->belongsTo('App\Models\Warehouse');
    }

    public function goods(){
        return $this->hasMany('App\Models\Goods');
    }

    public function materialRequests()
    {
        return $this->hasMany('App\Models\MaterialRequest','request_by_user_id');
    }

}
