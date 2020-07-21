<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContractInstallment extends Model
{
	protected $primaryKey = 'ciid';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
					        'contract_detail_id',
					        'contract_installment_amount', 
					        'contract_installment_date'
					    ];


    public function contract_detail()
    {
        return $this->belongsTo('App\ContractDetail', 'contract_detail_id', 'cd_id');
    }
}
