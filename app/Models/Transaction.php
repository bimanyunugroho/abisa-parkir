<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Transaction extends Model
{
    use HasFactory, HasSlug, SoftDeletes;

    protected $fillable = [
        'parking_area_id',
        'parking_rate_id',
        'user_id',
        'no_ticket',
        'license_plate',
        'slug',
        'entry_time',
        'exit_time',
        'duration',
        'total_cost',
        'payment',
        'change_pay',
        'status'
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['license_plate', 'entry_time'])
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parkingArea()
    {
        return $this->belongsTo(ParkingArea::class);
    }

    public function parkingRate()
    {
        return $this->belongsTo(ParkingRate::class);
    }

    protected static function booted()
    {
        static::created(function ($transaction) {
            $transaction->updateMonitoringParking(1);
        });

        static::updated(function ($transaction) {
            if ($transaction->isDirty('exit_time') && $transaction->exit_time !== null) {
                $transaction->updateMonitoringParking(-1);
            }
        });
    }

        protected function updateMonitoringParking($increment)
    {
        $parkingArea = $this->parkingArea;

        if (!$parkingArea) {
            return;
        }

        $status = $parkingArea->monitoringParking;
        if (!$status) {
            $status = $parkingArea->monitoringParking()->create([
                'used' => 0,
                'available' => $parkingArea->capacity
            ]);
        }

        $status->used += $increment;
        $status->available = $parkingArea->capacity - $status->used;
        $status->save();
    }


}
