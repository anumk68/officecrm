<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactPersons extends Model
{
    protected $fillable = [
        'name',
        'emails',
        'contact_numbers',
        "country",
        "state",
        "city",
        "village",
        "zip",
        "sub_country",
        "sub_state",
        'sub_city',
        'sub_village',
        'sub_zip',
    ];

    protected $casts = [

        'emails' => 'array',
        'contact_numbers' => 'array',
    ];

    public function countryData()
    {
        return $this->belongsTo(Country::class, 'country');
    }

    public function stateData()
    {
        return $this->belongsTo(State::class, 'state');
    }

    public function cityData()
    {
        return $this->belongsTo(City::class, 'city');
    }

    public function subCountryData()
    {
        return $this->belongsTo(Country::class, 'sub_country');
    }

    public function subStateData()
    {
        return $this->belongsTo(State::class, 'sub_state');
    }

    public function subCityData()
    {
        return $this->belongsTo(City::class, 'sub_city');
    }


}
