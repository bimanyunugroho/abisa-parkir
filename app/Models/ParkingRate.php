<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class ParkingRate extends Model
{
    use HasFactory, HasSlug, SoftDeletes;

    protected $fillable = [
        'vehicle_id',
        'time_interval',
        'cost',
        'slug'
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['vehicle_id', 'time_interval', 'cost'])
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
