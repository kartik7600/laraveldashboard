<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $primaryKey = 'cid';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
					        'client_detail_id'
					    ];


    public function users()
    {
        return $this->belongsTo('App\User', 'user_id', 'uid');
    }


    public function vatReportsSubmitted()
    {
        return $this->hasMany('App\VatReportSubmitted', 'contract_id', 'cid');
    }
}