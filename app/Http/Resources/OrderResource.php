<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'user_id'    => $this->user_id,
            'medicines'  => MedicineResource::collection($this->medicines),
            'status'     => $this->status,
            'created_at' => $this->created_at,
            'pharmacy'   => new PharmacyResource($this->pharmacy),
        ];
    }
}
