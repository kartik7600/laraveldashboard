<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VatReportSubmittedFile extends Model
{
    protected $primaryKey = 'vrsfid';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
					        'manager_id',
					        'trn_id', 
					        'vat_report_file_name',
                            'vat_report_file',
					    ];
}
