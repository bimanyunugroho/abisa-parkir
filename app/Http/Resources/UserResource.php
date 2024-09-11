<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name'  => $this->name,
            'username'  => $this->username,
            'role'  => new RoleResource($this->whenLoaded('role')),
            'status' => $this->status,
            'slug' => $this->slug,
            'icon' => $this->icon,
            'email' => $this->email,
            'icon_public_id' => $this->icon_public_id,
            'created_at'    => $this->created_at->format('Y-m-d H:i:s')
        ];
    }
}
