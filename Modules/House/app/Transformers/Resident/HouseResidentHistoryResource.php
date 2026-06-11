<?php

namespace Modules\House\Transformers\Resident;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\House\Transformers\House\HouseResource;

class HouseResidentHistoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'startDate' => $this->start_date,
            'endDate' => $this->end_date,
            'isActive' => $this->is_active,
            'house' => new HouseResource($this->whenLoaded('house')),
        ];
    }
}
