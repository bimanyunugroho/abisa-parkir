<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ParkingRateResource extends JsonResource
{
    /**
     * Initialize the resource instance.
     *
     * @return void
     */
    public function __construct($resource)
    {
        parent::__construct($resource);
        static::withoutWrapping();
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'vehicle' => new VehicleResource($this->whenLoaded('vehicle')),
            'time_interval' => $this->time_interval,
            'cost' => $this->cost,
            'slug' => $this->slug,
            'created_at' => $this->created_at->format('Y-m-d H:i:s')
        ];
    }
}
