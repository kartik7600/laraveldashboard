<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContractDetail extends Model
{
    protected $primaryKey = 'cd_id';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
					        'contract_id',
					        'contract_amount', 
					        'contract_start_date',
					        'contract_end_date',
                            'contract_installments',
                            'contract_total_installments'
					    ];


    public function contractServices()
    {
        return $this->hasMany('App\ContractService', 'contract_detail_id', 'cd_id');
    }

    public function contractInstallments()
    {
        return $this->hasMany('App\ContractInstallment', 'contract_detail_id', 'cd_id');
    }

}