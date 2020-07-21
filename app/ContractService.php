<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContractService extends Model
{
    protected $primaryKey = 'csid';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
					        'contract_detail_id',
					        'service_id'
					    ];


    public function contract()
    {
        return $this->belongsTo('App\Contract', 'contract_detail_id', 'cd_id');
    }
}
