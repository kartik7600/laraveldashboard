<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientUploadedReport extends Model
{
    protected $primaryKey = 'curid';
    
    protected $table = 'client_uploaded_reports';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
                            'user_id',
                            'service_id',
                            'report_file',
                        ];


    public function service()
    {
        return $this->belongsTo('App\Service', 'service_id', 'sid');
    }
}
