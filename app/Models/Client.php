<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $table = 'clients';
    protected $primaryKey = 'id';

    public function countryInfo() {
        return $this->belongsTo('App\Models\Country', 'country_id', 'id');
    }
}
