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
        'parking_session_id',
        'amount',
        'user_id',
        'payment_method',
        'slug'
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['parking_session_id', 'amount', 'payment_method'])
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function parkingSession()
    {
        return $this->belongsTo(ParkingSession::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
