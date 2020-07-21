<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TRN extends Model
{
    protected $primaryKey = 'tid';

    protected $table = 'trns';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
                            'client_detail_id',
                            'trn_tax_register_number',
                            'trn_company_type',
                            'trn_tax_register_date',
                            'trn_tax_period',
                            'trn_first_tax_period_start',
                            'trn_first_tax_period_start_year',
                            'trn_first_tax_period_end',
                            'trn_first_tax_period_end_year',
                            'trn_vat_certificate', 
                            'trn_trade_license',
                            'trn_status',
                        ];


    public function clientDetails()
    {
        return $this->belongsTo('App\ClientDetail', 'client_detail_id', 'cdid');
    }

    public function companyDetials()
    {
        return $this->hasMany('App\CompanyDetail', 'trn_id', 'tid');
    }
}
