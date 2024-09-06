<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class MonitoringParking extends Model
{
    use HasFactory, HasSlug, SoftDeletes;

    protected $fillable = [
        'parking_area_id',
        'used',
        'available',
        'slug'
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['parking_area_id', 'used', 'available'])
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function parkingArea()
    {
        return $this->belongsTo(ParkingArea::class);
    }
}
