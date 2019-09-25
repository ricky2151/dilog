<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class CogsComponent extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'name','value','info','cogs_id'
    ];

    public function cogs(){
        return $this->belongsTo('App\Models\Cogs');
    }
}
