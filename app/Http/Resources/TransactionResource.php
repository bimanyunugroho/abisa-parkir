<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            'parking_area'  => new ParkingAreaResource($this->whenLoaded('parkingArea')),
            'parking_rate'  => new ParkingRateResource($this->whenLoaded('parkingRate')),
            'vehicle' => $this->whenLoaded('parkingRate.vehicle', function() {
                return new VehicleResource($this->parkingRate->vehicle);
            }),
            'user'  => new UserResource($this->whenLoaded('user')),
            'no_ticket' => $this->no_ticket,
            'license_plate' => $this->license_plate,
            'slug' => $this->slug,
            'entry_time' => $this->entry_time,
            'exit_time' => $this->exit_time,
            'duration' => $this->duration,
            'total_cost' => $this->total_cost,
            'payment' => $this->payment,
            'change_pay' => $this->change_pay,
            'status'    => $this->status,
            'created_at'    => $this->created_at->format('Y-m-d H:i:s')
        ];
    }
}
