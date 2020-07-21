<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $primaryKey = 'did';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
					        'admin_id',
					        'trn_id', 
					        'document_name',
					        'document_file'
					    ];
}
