<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientService extends Model
{
    protected $table = 'client_service';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
                            'user_id',
                            'service_id',
                            'service_amount',
                            'purchase_date',
                            'service_duration'
                        ];

    /**
    * Get the user's full name.
    *
    * @return string
    */
    /*public function getFullNameAttribute()
    {
    	return "{$this->user_firstname}";
    }*/


    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function service(){
        return $this->belongsTo('App\Service');
    }
}
