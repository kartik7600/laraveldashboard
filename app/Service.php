<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $primaryKey = 'sid';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
                            'service_name'
                        ];


    public function clientservices(){
        return $this->hasMany('App\ClientService');
    }


    public function clientuploadedreports()
    {
        return $this->hasMany('App\ClientUploadedReport', 'service_id', 'sid');
    }
}
