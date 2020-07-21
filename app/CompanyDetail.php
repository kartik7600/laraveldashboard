<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyDetail extends Model
{
    protected $primaryKey = 'comid';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
                            'trn_id',
                            'company_name',
                            'company_work_designation',
                            'company_contact_person',
                            'company_mobile',
                            'company_phone',
                            'company_address', 
                            'company_city',
                            'company_state',
                            'company_country',
                            'company_zip_code',

                            'company_tax_number',
                            'company_tax_date',
                            'company_tax_period',
                            'company_first_tax_period_start',
                            'company_first_tax_period_end',
                            'company_vat_certificate',
                            'company_trade_license'
                        ];


    public function trns()
    {
        return $this->belongsTo('App\TRN', 'trn_id', 'tid');
    }
}
