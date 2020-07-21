<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientDetail extends Model
{
    protected $primaryKey = 'cdid';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
                            'user_id',
                            'manager_id',
                            'client_number',
                            'client_company',
                            'client_work_designation',
                            'client_tax_register_number',
                            'client_tax_register_date', 
                            'client_tax_period',
                            'client_first_tax_period_start',
                            'client_first_tax_period_end',
                            'client_payment_date',
                            'client_vat_certificate',
                            'client_trade_license',
                            'client_status'
                        ];


    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'uid');
    }

    public function trns()
    {
        return $this->hasMany('App\TRN', 'client_detail_id', 'cdid');
    }
}
