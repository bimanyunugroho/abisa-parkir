<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MonitoringParkingResource extends JsonResource
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
            'parking_area' => new ParkingAreaResource($this->whenLoaded('parkingArea')),
            'used' => $this->used,
            'available' => $this->available,
            'slug' => $this->slug,
            'created_at' => $this->created_at->format('Y-m-d H:i:s')
        ];
    }
}
