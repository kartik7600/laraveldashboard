<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VatReportSubmitted extends Model
{
    protected $primaryKey = 'vrsid';

    protected $table = 'vat_report_submitted';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
					        'manager_id',
					        'contract_id',
                            'trn_id',
					        'vat_report_duration',
                            'vat_report_submission_deadline',
					        'vat_report_note'
					    ];


    public function vatReportContract()
    {
        return $this->belongsTo('App\Contract', 'contract_id', 'cid');
    }
}
