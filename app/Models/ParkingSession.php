<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class ParkingSession extends Model
{
    use HasFactory, HasSlug, SoftDeletes;

    protected $fillable = [
        'vehicle_id',
        'parking_area_id',
        'user_id',
        'entry_time',
        'exit_time',
        'duration',
        'total_cost',
        'status',
        'slug'
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['vehicle_id', 'parking_area_id', 'entry_time', 'exit_time', 'duration', 'total_cost'])
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

    public function parkingArea()
    {
        return $this->belongsTo(ParkingArea::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }
}
