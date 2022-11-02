<?php

namespace Pimeo\Models;

class BuildingComponent extends Model
{
    const ROOFS = 'roofs';
    const FOUNDATIONS = 'foundations';
    const WALLS = 'walls';
    const BRIDGES = 'bridges';
    const PARKING_DECKS = 'parking_decks';
    const BALCONIES_PLAZA_DECKS = 'balconies_and_plaza_decks';
    const FOUNTAINS_PONDS = 'fountains_and_ponds';
    const INDOOR_APPLICATIONS = 'indoor_applications';

    protected $table = 'building_components';

    public $timestamps = false;

    protected $fillable = [
        'code'
    ];

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function systems()
    {
        return $this->belongsToMany(System::class, 'system_building_component');
    }

    public function scopeForCurrentCompany($query)
    {
        return $query->whereCompanyId(current_company()->id);
    }

    public function getAbbrAttribute()
    {
        $words = explode('_', $this->attributes['code']);
        $abbr  = '';
        foreach ($words as $word) {
            $abbr .= $word[0];
        }
        $abbr = strtoupper($abbr);

        return $abbr;
    }
}
